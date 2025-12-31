<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module as NwidartModule;

class ModuleController extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURATION
    |--------------------------------------------------------------------------
    */
    
    // Maximum ZIP file size (50MB)
    protected int $maxZipSize = 50 * 1024 * 1024;
    
    // Forbidden file extensions in ZIP
    protected array $forbiddenExtensions = [
        'exe', 'sh', 'bat', 'phar', 'php.dist', 'com', 'cmd', 'vbs', 'ps1', 'jar'
    ];
    
    // Required files/folders for valid module
    protected array $requiredModuleFiles = [
        'module.json',
        'Providers',
    ];

    /*
    |--------------------------------------------------------------------------
    | INDEX - List all modules
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->authorize('modules.modules.read');
        $folderModules = NwidartModule::all();
        $modules = [];

        foreach ($folderModules as $name => $module) {
            $json = $this->getModuleJson($name);
            $alias = strtolower($name);
            $dbRecord = Module::where('alias', $alias)->first();

            $modules[] = [
                'name' => $json['name'] ?? $name,
                'alias' => $alias,
                'description' => $json['description'] ?? null,
                'version' => $json['version'] ?? '1.0.0',
                'requires' => $json['requires'] ?? $json['dependencies'] ?? [],
                'is_installed' => $dbRecord ? true : false,
                'is_active' => $dbRecord ? $dbRecord->is_active : false,
                'is_core' => $dbRecord ? $dbRecord->is_core : false,
            ];
        }

        return view('admin.modules.index', compact('modules'));
    }

    /*
    |--------------------------------------------------------------------------
    | INSTALL - Add module to database and run migrations
    |--------------------------------------------------------------------------
    */
    public function install($alias)
    {
        $this->authorize('modules.modules.install');
        $name = $this->getModuleName($alias);
        $nwidart = NwidartModule::find($name);

        // Check if folder exists
        if (!$nwidart) {
            return back()->with('error', 'Module folder not found');
        }

        // Check if already installed
        if (Module::where('alias', strtolower($alias))->exists()) {
            return back()->with('error', 'Module already installed');
        }

        $json = $this->getModuleJson($name);

        // CHECK REQUIRED DEPENDENCIES
        $missingDeps = $this->checkRequiredDependencies($json);
        if (!empty($missingDeps)) {
            return back()->with('error',
                'Missing required modules: ' . implode(', ', $missingDeps) .
                '. Please install them first.'
            );
        }

        // CHECK CIRCULAR DEPENDENCY
        if ($this->hasCircularDependency($alias)) {
            return back()->with('error', 'Circular dependency detected! Module cannot be installed.');
        }

        // VERIFY MODULE INTEGRITY
        $modulePath = base_path("Modules/{$name}");
        $integrityErrors = $this->verifyModuleIntegrity($modulePath);
        if (!empty($integrityErrors)) {
            return back()->with('error', 'Module integrity check failed: ' . implode(', ', $integrityErrors));
        }

        // Create database record
        Module::create([
            'name' => $json['name'] ?? $name,
            'alias' => strtolower($alias),
            'description' => $json['description'] ?? null,
            'version' => $json['version'] ?? '1.0.0',
            'is_core' => $json['is_core'] ?? false,
            'sort_order' => $json['priority'] ?? 0,
            'is_installed' => true,
            'is_active' => true,
            'installed_at' => now(),
        ]);

        // Enable in filesystem
        $nwidart->enable();

        // Run module migrations
        $migrationResult = $this->runModuleMigration($name);

        $this->clearCache();

        if ($migrationResult['success']) {
            $message = 'Module installed successfully!';
            if (!empty($migrationResult['message'])) {
                $message .= ' ' . $migrationResult['message'];
            }
            return back()->with('success', $message);
        } else {
            return back()->with('warning', "Module installed but {$migrationResult['message']}");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVATE - Turn module ON
    |--------------------------------------------------------------------------
    */
    public function activate($alias)
    {
        $this->authorize('modules.modules.activate');
        $module = Module::where('alias', strtolower($alias))->first();
        $name = $this->getModuleName($alias);
        $nwidart = NwidartModule::find($name);

        if (!$module || !$nwidart) {
            return back()->with('error', 'Module not found');
        }

        // Check dependencies before activating
        $json = $this->getModuleJson($name);
        $missingDeps = $this->checkRequiredDependencies($json);
        if (!empty($missingDeps)) {
            return back()->with('error',
                'Cannot activate. Missing required modules: ' . implode(', ', $missingDeps)
            );
        }

        $nwidart->enable();
        $module->update(['is_active' => true]);
        $this->clearCache();

        return back()->with('success', 'Module activated');
    }

    /*
    |--------------------------------------------------------------------------
    | DEACTIVATE - Turn module OFF
    |--------------------------------------------------------------------------
    */
    public function deactivate($alias)
    {
        $this->authorize('modules.modules.deactivate');
        $module = Module::where('alias', strtolower($alias))->first();
        $name = $this->getModuleName($alias);
        $nwidart = NwidartModule::find($name);

        if (!$module || !$nwidart) {
            return back()->with('error', 'Module not found');
        }

        if ($module->is_core) {
            return back()->with('error', 'Cannot deactivate core module');
        }

        // Check if other modules depend on this
        $dependents = $this->getActiveDependentModules($alias);
        if (!empty($dependents)) {
            return back()->with('error',
                'Cannot deactivate. These modules depend on it: ' . implode(', ', $dependents) .
                '. Please deactivate them first.'
            );
        }

        $nwidart->disable();
        $module->update(['is_active' => false]);
        $this->clearCache();

        return back()->with('success', 'Module deactivated');
    }

    /*
    |--------------------------------------------------------------------------
    | UNINSTALL - Rollback migrations + Remove from DB (keeps files)
    |--------------------------------------------------------------------------
    */
    public function uninstall($alias)
    {
        $this->authorize('modules.modules.uninstall');
        $module = Module::where('alias', strtolower($alias))->first();

        if (!$module) {
            return back()->with('error', 'Module not found in database');
        }

        if ($module->is_core) {
            return back()->with('error', 'Cannot uninstall core module');
        }

        // CHECK DEPENDENCIES FIRST
        $dependents = $this->getActiveDependentModules($alias);
        if (!empty($dependents)) {
            return back()->with('error',
                'Cannot uninstall. These modules depend on it: ' . implode(', ', $dependents) .
                '. Please uninstall them first.'
            );
        }

        $name = $this->getModuleName($alias);
        $nwidart = NwidartModule::find($name);

        // ROLLBACK MIGRATIONS WITH FOREIGN KEY HANDLING
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Artisan::call('module:migrate-rollback', ['module' => $name, '--force' => true]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $output = Artisan::output();
            Log::info("Migration rollback for {$name}: " . $output);
        } catch (\Exception $e) {
            Log::warning("Migration rollback failed for {$name}, trying manual drop: " . $e->getMessage());
            $this->manuallyDropModuleTables($name);
        }

        // Disable the module
        if ($nwidart) {
            $nwidart->disable();
        }

        // Remove from status file
        $this->removeFromStatusFile($name);

        // Delete from DB only (keep files)
        $module->delete();
        $this->clearCache();

        return back()->with('success', 'Module uninstalled successfully. Files kept for reinstallation.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE - Remove files from Modules folder
    |--------------------------------------------------------------------------
    */
    public function delete($alias)
    {
        $this->authorize('modules.modules.delete');
        // Check if module is installed in DB - prevent deletion
        if (Module::where('alias', strtolower($alias))->exists()) {
            return back()->with('error', 'Please uninstall the module first before deleting files');
        }

        // Find module path
        $path = $this->findModulePath($alias);
        $name = $path ? basename($path) : null;

        if (!$path) {
            return back()->with('error', 'Module folder not found');
        }

        // Disable in nwidart first
        $nwidart = NwidartModule::find($name);
        if ($nwidart) {
            $nwidart->disable();
        }

        // Remove from status file
        $this->removeFromStatusFile($name);

        // Delete folder with multiple methods
        $deleted = $this->deleteModuleFolder($path);

        if (!$deleted) {
            return back()->with('error', 'Failed to delete folder. Please delete manually via FTP/cPanel: ' . $path);
        }

        $this->clearCache();

        return back()->with('success', "Module '{$name}' deleted successfully");
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD ZIP - Upload and install module from ZIP
    |--------------------------------------------------------------------------
    */
    public function uploadZip(Request $request)
    {
        $this->authorize('modules.modules.upload');
        if (!$request->hasFile('module_zip')) {
            return back()->with('error', 'Please select a ZIP file');
        }

        $file = $request->file('module_zip');

        // VALIDATION 1: Check extension
        if ($file->getClientOriginalExtension() !== 'zip') {
            return back()->with('error', 'Only ZIP files are allowed');
        }

        // VALIDATION 2: Check file size
        if ($file->getSize() > $this->maxZipSize) {
            $maxMB = $this->maxZipSize / 1024 / 1024;
            return back()->with('error', "ZIP file too large. Maximum {$maxMB}MB allowed.");
        }

        // VALIDATION 3: Check MIME type
        $allowedMimes = ['application/zip', 'application/x-zip-compressed', 'application/x-zip'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return back()->with('error', 'Invalid file type. Only ZIP files are allowed.');
        }

        $zip = new \ZipArchive();

        if ($zip->open($file->getPathname()) !== true) {
            return back()->with('error', 'Cannot open ZIP file. File may be corrupted.');
        }

        // VALIDATION 4: Check for dangerous files inside ZIP
        $securityCheck = $this->validateZipContents($zip);
        if ($securityCheck !== true) {
            $zip->close();
            return back()->with('error', $securityCheck);
        }

        $tempDir = storage_path('app/temp_module_' . time());
        $zip->extractTo($tempDir);
        $zip->close();

        $moduleJsonPath = $this->findModuleJson($tempDir);

        if (!$moduleJsonPath) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'module.json not found in ZIP. Invalid module structure.');
        }

        $moduleJson = json_decode(File::get($moduleJsonPath), true);
        
        // VALIDATION 5: Check module.json is valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Invalid module.json file. JSON parse error.');
        }

        $moduleName = $moduleJson['name'] ?? null;

        if (!$moduleName) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Module name not found in module.json');
        }

        // VALIDATION 6: Check module name is safe
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $moduleName)) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Invalid module name. Only alphanumeric characters allowed, must start with letter.');
        }

        $moduleDir = base_path("Modules/{$moduleName}");

        if (File::isDirectory($moduleDir)) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Module already exists');
        }

        // Check dependencies before installing
        $missingDeps = $this->checkRequiredDependencies($moduleJson);
        if (!empty($missingDeps)) {
            File::deleteDirectory($tempDir);
            return back()->with('error',
                'Missing required modules: ' . implode(', ', $missingDeps) .
                '. Please install them first.'
            );
        }

        // VALIDATION 7: Check for circular dependency
        $alias = strtolower($moduleName);
        if ($this->wouldCreateCircularDependency($alias, $moduleJson)) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Installing this module would create a circular dependency!');
        }

        $sourceDir = dirname($moduleJsonPath);
        
        // VALIDATION 8: Verify module integrity before copying
        $integrityErrors = $this->verifyModuleIntegrity($sourceDir);
        if (!empty($integrityErrors)) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Module integrity check failed: ' . implode(', ', $integrityErrors));
        }

        File::copyDirectory($sourceDir, $moduleDir);
        File::deleteDirectory($tempDir);

        // Set correct permissions
        $this->setPermissionsRecursive($moduleDir, 0755, 0644);

        // Update status file
        $this->updateStatusFile($moduleName, true);

        // Create database record
        Module::create([
            'name' => $moduleJson['name'] ?? $moduleName,
            'alias' => $alias,
            'description' => $moduleJson['description'] ?? null,
            'version' => $moduleJson['version'] ?? '1.0.0',
            'is_core' => $moduleJson['is_core'] ?? false,
            'sort_order' => $moduleJson['priority'] ?? 0,
            'is_installed' => true,
            'is_active' => true,
            'installed_at' => now(),
        ]);

        // Clear cache and rescan
        $this->clearCache();

        try {
            NwidartModule::scan();
            Log::info("Module scan completed after uploading {$moduleName}");
        } catch (\Exception $e) {
            Log::warning("Module scan failed: " . $e->getMessage());
        }

        usleep(500000); // 0.5 seconds delay

        // Run migrations
        $migrationResult = $this->runModuleMigration($moduleName);

        $this->clearCache();

        if ($migrationResult['success']) {
            $message = "Module '{$moduleName}' installed successfully!";
            if (!empty($migrationResult['message'])) {
                $message .= " " . $migrationResult['message'];
            }
            return back()->with('success', $message);
        } else {
            return back()->with('warning', "Module '{$moduleName}' installed but {$migrationResult['message']}");
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RUN MIGRATION - Manual migration runner
    |--------------------------------------------------------------------------
    */
    public function runMigration($alias)
    {
        $this->authorize('modules.modules.migrate');
        $module = Module::where('alias', strtolower($alias))->first();

        if (!$module) {
            return back()->with('error', 'Module not found in database');
        }

        $name = $this->getModuleName($alias);
        $result = $this->runModuleMigration($name);

        if ($result['success']) {
            $message = 'Migration completed successfully!';
            if (!empty($result['message'])) {
                $message .= ' ' . $result['message'];
            }
            return back()->with('success', $message);
        } else {
            return back()->with('error', "Migration failed: {$result['message']}");
        }
    }

    /*
    |==========================================================================
    | ZIP SECURITY VALIDATION
    |==========================================================================
    */

    /**
     * Validate ZIP contents for security
     */
    protected function validateZipContents(\ZipArchive $zip): bool|string
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            
            // Check for path traversal attack
            if (strpos($filename, '..') !== false) {
                return 'ZIP contains path traversal attempt. Upload rejected.';
            }

            // Check for absolute paths
            if (strpos($filename, '/') === 0 || preg_match('/^[a-zA-Z]:/', $filename)) {
                return 'ZIP contains absolute paths. Upload rejected.';
            }

            // Check for hidden files (except .gitkeep, .gitignore)
            $basename = basename($filename);
            if (strpos($basename, '.') === 0 && !in_array($basename, ['.gitkeep', '.gitignore', '.env.example'])) {
                return "ZIP contains hidden file: {$basename}. Upload rejected.";
            }

            // Check for forbidden extensions
            foreach ($this->forbiddenExtensions as $ext) {
                if (preg_match('/\.' . preg_quote($ext, '/') . '$/i', $filename)) {
                    return "ZIP contains forbidden file type: .{$ext}. Upload rejected.";
                }
            }

            // Check for PHP files in suspicious locations
            if (preg_match('/\.php$/i', $filename)) {
                // Allow PHP only in specific directories
                $allowedPaths = ['Providers/', 'Http/', 'Models/', 'Database/', 'Console/', 'Services/', 'Helpers/', 'Traits/', 'Events/', 'Listeners/', 'Jobs/', 'Mail/', 'Notifications/', 'Policies/', 'Rules/', 'Exceptions/'];
                $isAllowed = false;
                
                foreach ($allowedPaths as $path) {
                    if (strpos($filename, $path) !== false) {
                        $isAllowed = true;
                        break;
                    }
                }
                
                // Also allow PHP in Routes folder and root Config
                if (strpos($filename, 'Routes/') !== false || strpos($filename, 'Config/') !== false) {
                    $isAllowed = true;
                }
                
                // Block PHP in Resources/views (blade files are ok)
                if (strpos($filename, 'Resources/') !== false && !preg_match('/\.blade\.php$/i', $filename) && preg_match('/\.php$/i', $filename)) {
                    return "PHP files not allowed in Resources folder: {$filename}";
                }
            }
        }

        return true;
    }

    /*
    |==========================================================================
    | CIRCULAR DEPENDENCY CHECK
    |==========================================================================
    */

    /**
     * Check for circular dependencies in existing modules
     */
    protected function hasCircularDependency($alias, $visited = []): bool
    {
        $alias = strtolower($alias);
        
        if (in_array($alias, $visited)) {
            return true; // Circular dependency detected!
        }

        $visited[] = $alias;
        $name = $this->getModuleName($alias);
        $deps = $this->getModuleDependencies($name);

        foreach ($deps as $dep) {
            if ($this->hasCircularDependency(strtolower($dep), $visited)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if installing a new module would create circular dependency
     */
    protected function wouldCreateCircularDependency($newAlias, $newModuleJson): bool
    {
        $newAlias = strtolower($newAlias);
        $requires = $newModuleJson['requires'] ?? $newModuleJson['dependencies'] ?? [];

        foreach ($requires as $dep) {
            $depAlias = strtolower($dep);
            
            // Check if the dependency depends on the new module (directly or indirectly)
            if ($this->dependsOn($depAlias, $newAlias)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if moduleA depends on moduleB (directly or indirectly)
     */
    protected function dependsOn($moduleA, $moduleB, $visited = []): bool
    {
        $moduleA = strtolower($moduleA);
        $moduleB = strtolower($moduleB);

        if ($moduleA === $moduleB) {
            return true;
        }

        if (in_array($moduleA, $visited)) {
            return false; // Already checked, avoid infinite loop
        }

        $visited[] = $moduleA;
        $name = $this->getModuleName($moduleA);
        $deps = $this->getModuleDependencies($name);

        foreach ($deps as $dep) {
            if ($this->dependsOn(strtolower($dep), $moduleB, $visited)) {
                return true;
            }
        }

        return false;
    }

    /*
    |==========================================================================
    | MODULE INTEGRITY CHECK
    |==========================================================================
    */

    /**
     * Verify module has required files and structure
     */
    protected function verifyModuleIntegrity($modulePath): array
    {
        $errors = [];

        // Check required files/folders exist
        foreach ($this->requiredModuleFiles as $item) {
            $path = $modulePath . '/' . $item;
            if (!file_exists($path)) {
                $errors[] = "Missing: {$item}";
            }
        }

        // Validate module.json content
        $jsonPath = $modulePath . '/module.json';
        if (file_exists($jsonPath)) {
            $content = file_get_contents($jsonPath);
            $json = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = 'Invalid module.json: ' . json_last_error_msg();
            } else {
                // Check required fields in module.json
                if (empty($json['name'])) {
                    $errors[] = 'module.json missing "name" field';
                }
            }
        }

        // Check Providers folder has ServiceProvider
        $providersPath = $modulePath . '/Providers';
        if (is_dir($providersPath)) {
            $hasServiceProvider = false;
            $files = scandir($providersPath);
            
            foreach ($files as $file) {
                if (preg_match('/ServiceProvider\.php$/', $file)) {
                    $hasServiceProvider = true;
                    break;
                }
            }

            if (!$hasServiceProvider) {
                $errors[] = 'Missing ServiceProvider in Providers folder';
            }
        }

        return $errors;
    }

    /*
    |==========================================================================
    | DEPENDENCY MANAGEMENT HELPERS
    |==========================================================================
    */

    /**
     * Get module dependencies from module.json
     */
    protected function getModuleDependencies($moduleName): array
    {
        $json = $this->getModuleJson($moduleName);
        return $json['requires'] ?? $json['dependencies'] ?? [];
    }

    /**
     * Check if required dependencies are installed and active
     */
    protected function checkRequiredDependencies(array $moduleJson): array
    {
        $requires = $moduleJson['requires'] ?? $moduleJson['dependencies'] ?? [];
        $missingDeps = [];

        foreach ($requires as $requiredModule) {
            $requiredAlias = strtolower($requiredModule);
            $installed = Module::where('alias', $requiredAlias)
                ->where('is_active', true)
                ->exists();

            if (!$installed) {
                $missingDeps[] = $requiredModule;
            }
        }

        return $missingDeps;
    }

    /**
     * Check which ACTIVE modules depend on this module
     */
    protected function getActiveDependentModules($alias): array
    {
        $dependents = [];
        $allModules = NwidartModule::all();
        $targetAlias = strtolower($alias);

        foreach ($allModules as $name => $module) {
            $json = $this->getModuleJson($name);
            $requires = $json['requires'] ?? $json['dependencies'] ?? [];

            // Normalize to lowercase for comparison
            $requires = array_map('strtolower', $requires);

            if (in_array($targetAlias, $requires)) {
                // Check if this dependent module is installed and active
                $dependentAlias = strtolower($name);
                $isActive = Module::where('alias', $dependentAlias)
                    ->where('is_active', true)
                    ->exists();

                if ($isActive) {
                    $dependents[] = $name;
                }
            }
        }

        return $dependents;
    }

    /**
     * Get all modules that depend on this module (installed or not)
     */
    protected function getAllDependentModules($alias): array
    {
        $dependents = [];
        $allModules = NwidartModule::all();
        $targetAlias = strtolower($alias);

        foreach ($allModules as $name => $module) {
            $json = $this->getModuleJson($name);
            $requires = $json['requires'] ?? $json['dependencies'] ?? [];
            $requires = array_map('strtolower', $requires);

            if (in_array($targetAlias, $requires)) {
                $dependents[] = $name;
            }
        }

        return $dependents;
    }

    /*
    |==========================================================================
    | DATABASE & MIGRATION HELPERS
    |==========================================================================
    */

    /**
     * Manually drop tables with foreign key handling
     */
    protected function manuallyDropModuleTables($moduleName)
    {
        $migrationPath = base_path("Modules/{$moduleName}/Database/Migrations");

        if (!File::isDirectory($migrationPath)) {
            return;
        }

        $migrations = File::files($migrationPath);
        $tablesToDrop = [];

        // Parse migration files to find table names
        foreach ($migrations as $migration) {
            $content = File::get($migration->getPathname());

            // Look for Schema::create patterns
            preg_match_all("/Schema::create\('([^']+)'/", $content, $matches);
            if (!empty($matches[1])) {
                $tablesToDrop = array_merge($tablesToDrop, $matches[1]);
            }
        }

        // Drop tables in reverse order
        $tablesToDrop = array_reverse(array_unique($tablesToDrop));

        // DISABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tablesToDrop as $table) {
            try {
                if (Schema::hasTable($table)) {
                    Schema::dropIfExists($table);
                    Log::info("Dropped table: {$table}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to drop table {$table}: " . $e->getMessage());
            }
        }

        // RE-ENABLE FOREIGN KEY CHECKS
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Run module migration with proper error handling
     */
    protected function runModuleMigration($moduleName): array
    {
        $migrationPath = base_path("Modules/{$moduleName}/Database/Migrations");

        if (!File::isDirectory($migrationPath)) {
            return ['success' => true, 'message' => ''];
        }

        $migrationFiles = File::files($migrationPath);
        if (count($migrationFiles) === 0) {
            return ['success' => true, 'message' => ''];
        }

        try {
            Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
            $output = Artisan::output();

            Log::info("Migration for {$moduleName}: " . $output);

            return ['success' => true, 'message' => ''];
        } catch (\Exception $e) {
            Log::error("Migration failed for {$moduleName}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage()
            ];
        }
    }

    /*
    |==========================================================================
    | FILE SYSTEM HELPERS
    |==========================================================================
    */

    /**
     * Get module.json data
     */
    protected function getModuleJson($name): array
    {
        $path = base_path("Modules/{$name}/module.json");

        if (File::exists($path)) {
            return json_decode(File::get($path), true) ?? [];
        }

        return [];
    }

    /**
     * Find module.json in directory
     */
    protected function findModuleJson($dir)
    {
        $files = File::allFiles($dir);

        foreach ($files as $file) {
            if ($file->getFilename() === 'module.json') {
                return $file->getPathname();
            }
        }

        return null;
    }

    /**
     * Get proper module name (handles case variations)
     */
    protected function getModuleName($alias): string
    {
        // First try to find exact folder match
        $modulesPath = base_path('Modules');

        if (File::isDirectory($modulesPath)) {
            $folders = File::directories($modulesPath);

            foreach ($folders as $folder) {
                $folderName = basename($folder);
                if (strtolower($folderName) === strtolower($alias)) {
                    return $folderName;
                }
            }
        }

        // Fallback to ucfirst
        return ucfirst($alias);
    }

    /**
     * Find module path by alias
     */
    protected function findModulePath($alias): ?string
    {
        $modulesPath = base_path('Modules');

        if (File::isDirectory($modulesPath)) {
            $folders = File::directories($modulesPath);

            foreach ($folders as $folder) {
                $folderName = basename($folder);
                if (strtolower($folderName) === strtolower($alias)) {
                    return $folder;
                }
            }
        }

        // Fallback: Try common case variations
        $possibleNames = [
            $this->toPascalCase($alias),
            ucfirst($alias),
            strtolower($alias),
            strtoupper($alias),
            $alias,
        ];

        foreach ($possibleNames as $tryName) {
            $tryPath = base_path("Modules/{$tryName}");
            if (File::isDirectory($tryPath)) {
                return $tryPath;
            }
        }

        return null;
    }

    /**
     * Convert string to PascalCase
     */
    protected function toPascalCase($string): string
    {
        $string = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $string));

        $patterns = [
            'management' => 'Management',
            'service' => 'Service',
            'student' => 'Student',
            'calendar' => 'Calendar',
            'invoice' => 'Invoice',
            'payment' => 'Payment',
            'product' => 'Product',
            'order' => 'Order',
            'customer' => 'Customer',
            'user' => 'User',
            'admin' => 'Admin',
            'lead' => 'Lead',
            'ecommerce' => 'Ecommerce',
            'inventory' => 'Inventory',
            'crm' => 'CRM',
            'hrm' => 'HRM',
        ];

        foreach ($patterns as $word => $capitalized) {
            if (str_contains($string, $word)) {
                $string = str_replace($word, $capitalized, $string);
            }
        }

        if (strtolower($string) === preg_replace('/[^a-zA-Z0-9]+/', '', strtolower($string))) {
            $string = ucfirst($string);
        }

        return $string;
    }

    /**
     * Delete module folder with multiple methods
     */
    protected function deleteModuleFolder($path): bool
    {
        // Fix permissions first
        $this->setPermissionsRecursive($path, 0777, 0777);

        // Method 1: Laravel's deleteDirectory
        try {
            if (File::deleteDirectory($path)) {
                return true;
            }
        } catch (\Exception $e) {
            Log::warning("Laravel delete failed: " . $e->getMessage());
        }

        // Method 2: Custom recursive delete
        if (File::isDirectory($path)) {
            try {
                $this->deleteDirectoryRecursive($path);
                if (!File::isDirectory($path)) {
                    return true;
                }
            } catch (\Exception $e) {
                Log::warning("Recursive delete failed: " . $e->getMessage());
            }
        }

        // Method 3: Shell command
        if (File::isDirectory($path)) {
            try {
                $safePath = escapeshellarg($path);
                exec("rm -rf {$safePath} 2>&1", $output, $returnVar);

                if (!File::isDirectory($path)) {
                    return true;
                }
            } catch (\Exception $e) {
                Log::warning("Shell delete failed: " . $e->getMessage());
            }
        }

        return !File::isDirectory($path);
    }

    /**
     * Recursively delete directory
     */
    protected function deleteDirectoryRecursive($dir)
    {
        if (!is_dir($dir)) {
            return true;
        }

        @chmod($dir, 0777);

        $items = @scandir($dir);
        if ($items === false) {
            return false;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
            @chmod($itemPath, 0777);

            if (is_dir($itemPath)) {
                $this->deleteDirectoryRecursive($itemPath);
            } else {
                @unlink($itemPath);
            }
        }

        return @rmdir($dir);
    }

    /**
     * Set permissions recursively
     */
    protected function setPermissionsRecursive($path, $dirPermission = 0755, $filePermission = 0644)
    {
        if (!File::exists($path)) {
            return;
        }

        if (is_file($path)) {
            @chmod($path, $filePermission);
            return;
        }

        @chmod($path, $dirPermission);

        $items = @scandir($path);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $path . DIRECTORY_SEPARATOR . $item;

            if (is_dir($itemPath)) {
                $this->setPermissionsRecursive($itemPath, $dirPermission, $filePermission);
            } else {
                @chmod($itemPath, $filePermission);
            }
        }
    }

    /*
    |==========================================================================
    | STATUS FILE HELPERS
    |==========================================================================
    */

    /**
     * Update modules_statuses.json
     */
    protected function updateStatusFile($moduleName, $enabled = true)
    {
        $statusFile = storage_path('app/modules_statuses.json');
        $statuses = [];

        if (File::exists($statusFile)) {
            $statuses = json_decode(File::get($statusFile), true) ?? [];
        }

        $statuses[$moduleName] = $enabled;
        File::put($statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
    }

    /**
     * Remove from modules_statuses.json
     */
    protected function removeFromStatusFile($moduleName)
    {
        $statusFile = storage_path('app/modules_statuses.json');

        if (File::exists($statusFile)) {
            $statuses = json_decode(File::get($statusFile), true) ?? [];
            unset($statuses[$moduleName]);
            File::put($statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
        }
    }

    /*
    |==========================================================================
    | CACHE HELPER
    |==========================================================================
    */

    /**
     * Clear all caches
     */
    protected function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            Log::warning("Cache clear failed: " . $e->getMessage());
        }
    }
}