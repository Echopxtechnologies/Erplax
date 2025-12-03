<?php
namespace App\Http\Controllers;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module as NwidartModule;

class ModuleController extends Controller{
    //showing or getting all moudles fucntion 
    public function index(){
        $folderModules = NwidartModule::all(); // to get all module form moduel folder 
        $dbModules = Module::pluck('alias')->toArray(); // to get all modules from my databsae
        $modules = []; //what all moduels we got we can store here na 
        foreach ($folderModules as $name => $module) {
            $json = $this->getModuleJson($name);
            $alias = strtolower($name);
            
            // Check if installed in DB
            $dbRecord = Module::where('alias', $alias)->first();
            
            $modules[] = [
                'name' => $json['name'] ?? $name,
                'alias' => $alias,
                'description' => $json['description'] ?? null,
                'version' => $json['version'] ?? '1.0.0',
                'is_installed' => $dbRecord ? true : false,
                'is_active' => $dbRecord ? $dbRecord->is_active : false,
                'is_core' => $dbRecord ? $dbRecord->is_core : false,
            ];
        }
        
        return view('admin.modules.index', compact('modules'));
    }

    // install fucntion to add to db  cus default panra apo it doesnt save moduels in db so it will have install button 
    public function install($alias)
    {
        $name = ucfirst($alias);
        $nwidart = NwidartModule::find($name);
        
        // Check if folder exists
        if (!$nwidart) {
            return back()->with('error', 'Module folder not found');
        }
        
        // Check if already installed
        if (Module::where('alias', $alias)->exists()) {
            return back()->with('error', 'Module already installed');
        }
        
        $json = $this->getModuleJson($name);
        
        // Create database record
        Module::create([
            'name' => $json['name'] ?? $name,
            'alias' => $alias,
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
            // Only add extra info if there's something to say
            if (!empty($migrationResult['message'])) {
                $message .= ' ' . $migrationResult['message'];
            }
            return back()->with('success', $message);
        } else {
            return back()->with('warning', "Module installed but {$migrationResult['message']}");
        }
    }
    
    // ACTIVATE: Turn ON
    public function activate($alias)
    {
        $module = Module::where('alias', $alias)->first();
        $nwidart = NwidartModule::find(ucfirst($alias));

        if (!$module || !$nwidart) {
            return back()->with('error', 'Module not found');
        }

        $nwidart->enable();
        $module->update(['is_active' => true]);
        $this->clearCache();

        return back()->with('success', 'Module activated');
    }
   
    // DEACTIVATE: Turn OFF
    public function deactivate($alias)
    {
        $module = Module::where('alias', $alias)->first();
        $nwidart = NwidartModule::find(ucfirst($alias));

        if (!$module || !$nwidart) {
            return back()->with('error', 'Module not found');
        }

        if ($module->is_core) {
            return back()->with('error', 'Cannot deactivate core module');
        }

        $nwidart->disable();
        $module->update(['is_active' => false]);
        $this->clearCache();

        return back()->with('success', 'Module deactivated');
    }

    // ============================================
    // UNINSTALL: Rollback migrations + Remove from DB (keeps files)
    // ============================================
    public function uninstall($alias)
    {
        $module = Module::where('alias', $alias)->first();

        if (!$module) {
            return back()->with('error', 'Module not found in database');
        }

        if ($module->is_core) {
            return back()->with('error', 'Cannot uninstall core module');
        }

        $name = ucfirst($alias);
        $nwidart = NwidartModule::find($name);

        // ===== ROLLBACK MIGRATIONS TO DROP TABLES =====
        try {
            // Try to rollback module migrations
            Artisan::call('module:migrate-rollback', ['module' => $name, '--force' => true]);
            $output = Artisan::output();
            Log::info("Migration rollback for {$name}: " . $output);
        } catch (\Exception $e) {
            Log::warning("Migration rollback failed for {$name}, trying manual drop: " . $e->getMessage());
            // If rollback fails, try to manually drop tables
            $this->manuallyDropModuleTables($name);
        }

        // Disable the module
        if ($nwidart) {
            $nwidart->disable();
        }

        // Remove from status file
        $statusFile = storage_path('app/modules_statuses.json');
        if (File::exists($statusFile)) {
            $statuses = json_decode(File::get($statusFile), true) ?? [];
            unset($statuses[$name]);
            File::put($statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
        }

        // Delete from DB only (keep files)
        $module->delete();
        $this->clearCache();

        return back()->with('success', 'Module uninstalled and tables dropped. Files kept for reinstallation.');
    }

    /**
     * Manually drop tables created by module migrations
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

        // Drop tables in reverse order (to handle foreign keys)
        $tablesToDrop = array_reverse(array_unique($tablesToDrop));
        
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
    }

    // ============================================
    // DELETE: Remove files from Modules folder
    // ============================================
    public function delete($alias)
    {
        // Check if module is installed in DB - prevent deletion
        if (Module::where('alias', $alias)->exists()) {
            return back()->with('error', 'Please uninstall the module first before deleting files');
        }

        // IMPROVED: Scan actual folders and find matching module
        $modulesPath = base_path('Modules');
        $path = null;
        $name = null;

        if (File::isDirectory($modulesPath)) {
            $folders = File::directories($modulesPath);
            
            foreach ($folders as $folder) {
                $folderName = basename($folder);
                // Check if folder name matches alias (case-insensitive)
                if (strtolower($folderName) === strtolower($alias)) {
                    $path = $folder;
                    $name = $folderName;
                    break;
                }
            }
        }

        // Fallback: Try common case variations if not found
        if (!$path) {
            $possibleNames = [
                $this->toPascalCase($alias),  // StudentManagement from studentmanagement
                ucfirst($alias),               // Studentmanagement
                strtolower($alias),            // studentmanagement
                strtoupper($alias),            // STUDENTMANAGEMENT
                $alias,                        // as-is
            ];

            foreach ($possibleNames as $tryName) {
                $tryPath = base_path("Modules/{$tryName}");
                if (File::isDirectory($tryPath)) {
                    $path = $tryPath;
                    $name = $tryName;
                    break;
                }
            }
        }

        if (!$path) {
            return back()->with('error', 'Module folder not found');
        }

        // Disable in nwidart first
        $nwidart = NwidartModule::find($name);
        if ($nwidart) {
            $nwidart->disable();
        }

        // Remove from status file
        $statusFile = storage_path('app/modules_statuses.json');
        if (File::exists($statusFile)) {
            $statuses = json_decode(File::get($statusFile), true) ?? [];
            unset($statuses[$name]);
            File::put($statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
        }

        // ===== ENHANCED FOLDER DELETION =====
        // STEP 1: Fix permissions first
        $this->setPermissionsRecursive($path, 0777, 0777);

        // STEP 2: Try multiple deletion methods
        $deleted = false;
        $errors = [];

        // Method 1: Try Laravel's deleteDirectory
        try {
            if (File::deleteDirectory($path)) {
                $deleted = true;
            }
        } catch (\Exception $e) {
            $errors[] = "Laravel method failed: " . $e->getMessage();
        }

        // Method 2: If Laravel fails, try custom recursive delete
        if (!$deleted && File::isDirectory($path)) {
            try {
                $this->deleteDirectoryRecursive($path, $errors);
                if (!File::isDirectory($path)) {
                    $deleted = true;
                }
            } catch (\Exception $e) {
                $errors[] = "Recursive method failed: " . $e->getMessage();
            }
        }

        // Method 3: If still exists, try shell command
        if (!$deleted && File::isDirectory($path)) {
            try {
                $safePath = escapeshellarg($path);
                $output = [];
                $returnVar = 0;
                
                exec("rm -rf {$safePath} 2>&1", $output, $returnVar);
                
                if (!File::isDirectory($path)) {
                    $deleted = true;
                } else {
                    $errors[] = "Shell command failed: " . implode(', ', $output);
                }
            } catch (\Exception $e) {
                $errors[] = "Shell method failed: " . $e->getMessage();
            }
        }

        // Check final result
        if (!$deleted && File::isDirectory($path)) {
            $errorMessage = 'Failed to delete folder. ';
            if (!empty($errors)) {
                $errorMessage .= 'Errors: ' . implode(' | ', array_slice($errors, 0, 2));
            }
            $errorMessage .= ' Please delete manually via FTP/cPanel: ' . $path;
            
            return back()->with('error', $errorMessage);
        }

        $this->clearCache();

        return back()->with('success', "Module '{$name}' deleted successfully");
    }

    /**
     * Set permissions recursively on directory
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

    /**
     * Recursively delete directory - Enhanced version
     */
    protected function deleteDirectoryRecursive($dir, &$errors = [])
    {
        if (!is_dir($dir)) {
            return true;
        }

        @chmod($dir, 0777);

        $items = @scandir($dir);
        if ($items === false) {
            $errors[] = "Cannot read: " . basename($dir);
            return false;
        }
        
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
            @chmod($itemPath, 0777);

            if (is_dir($itemPath)) {
                $this->deleteDirectoryRecursive($itemPath, $errors);
            } else {
                $attempts = 0;
                $maxAttempts = 3;
                $deleted = false;
                
                while ($attempts < $maxAttempts && !$deleted) {
                    if (@unlink($itemPath)) {
                        $deleted = true;
                    } else {
                        usleep(100000);
                        $attempts++;
                    }
                }
                
                if (!$deleted) {
                    $errors[] = basename($itemPath);
                }
            }
        }

        $attempts = 0;
        $maxAttempts = 3;
        $deleted = false;
        
        while ($attempts < $maxAttempts && !$deleted) {
            if (@rmdir($dir)) {
                $deleted = true;
            } else {
                usleep(100000);
                $attempts++;
            }
        }
        
        if (!$deleted) {
            $errors[] = "Dir: " . basename($dir);
        }

        return $deleted;
    }

    // ============================================
    // HELPERS
    // ============================================
    
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

    protected function getModuleJson($name): array
    {
        $path = base_path("Modules/{$name}/module.json");
        
        if (File::exists($path)) {
            return json_decode(File::get($path), true) ?? [];
        }
        
        return [];
    }

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

    /**
     * Run module migration with proper error handling
     */
    protected function runModuleMigration($moduleName): array
    {
        $migrationPath = base_path("Modules/{$moduleName}/Database/Migrations");
        
        // Check if migration files exist
        if (!File::isDirectory($migrationPath)) {
            return [
                'success' => true,
                'message' => '' // No migrations = success, no need to mention
            ];
        }
        
        $migrationFiles = File::files($migrationPath);
        if (count($migrationFiles) === 0) {
            return [
                'success' => true,
                'message' => '' // No migrations = success, no need to mention
            ];
        }
        
        try {
            // Run migration
            Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
            $output = Artisan::output();
            
            Log::info("Migration for {$moduleName}: " . $output);
            
            // Check output for success indicators
            if (strpos($output, 'Migrated') !== false) {
                return [
                    'success' => true,
                    'message' => '' // Empty = clean success message
                ];
            } elseif (strpos($output, 'Nothing to migrate') !== false) {
                return [
                    'success' => true,
                    'message' => '' // Already migrated, no need to mention
                ];
            } else {
                // If no exception was thrown, assume success
                // Migration might have completed but output format is different
                return [
                    'success' => true,
                    'message' => '' // Treat as success, logs have details if needed
                ];
            }
        } catch (\Exception $e) {
            Log::error("Migration failed for {$moduleName}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Upload ZIP and install module - FULLY IMPROVED VERSION
     */
    public function uploadZip(Request $request)
    {
        if (!$request->hasFile('module_zip')) {
            return back()->with('error', 'Please select a ZIP file');
        }
        
        $file = $request->file('module_zip');
        
        if ($file->getClientOriginalExtension() !== 'zip') {
            return back()->with('error', 'Only ZIP files are allowed');
        }
        
        $zip = new \ZipArchive();
        
        if ($zip->open($file->getPathname()) !== true) {
            return back()->with('error', 'Cannot open ZIP file');
        }
        
        $tempDir = storage_path('app/temp_module_' . time());
        $zip->extractTo($tempDir);
        $zip->close();
        
        $moduleJsonPath = $this->findModuleJson($tempDir);
        
        if (!$moduleJsonPath) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'module.json not found in ZIP');
        }
        
        $moduleJson = json_decode(File::get($moduleJsonPath), true);
        $moduleName = $moduleJson['name'] ?? null;
        
        if (!$moduleName) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Module name not found in module.json');
        }
        
        $moduleDir = base_path("Modules/{$moduleName}");
        
        if (File::isDirectory($moduleDir)) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Module already exists');
        }
        
        $sourceDir = dirname($moduleJsonPath);
        File::copyDirectory($sourceDir, $moduleDir);
        File::deleteDirectory($tempDir);
        
        // SET CORRECT PERMISSIONS
        $this->setPermissionsRecursive($moduleDir, 0755, 0644);
        
        // Update status file
        $statusFile = storage_path('app/modules_statuses.json');
        $statuses = [];
        
        if (File::exists($statusFile)) {
            $statuses = json_decode(File::get($statusFile), true) ?? [];
        }
        
        $statuses[$moduleName] = true;
        File::put($statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
        
        $alias = strtolower($moduleName);
        
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
        
        // ========== IMPROVED MIGRATION HANDLING ==========
        
        // Clear cache FIRST
        $this->clearCache();
        
        // Rescan modules to register the new one
        if (class_exists('Nwidart\Modules\Facades\Module')) {
            try {
                NwidartModule::scan();
                Log::info("Module scan completed after uploading {$moduleName}");
            } catch (\Exception $e) {
                Log::warning("Module scan failed: " . $e->getMessage());
            }
        }
        
        // Small delay to ensure filesystem is ready
        usleep(500000); // 0.5 seconds
        
        // Run migrations with proper error handling
        $migrationResult = $this->runModuleMigration($moduleName);
        
        // Clear cache AGAIN after migration
        $this->clearCache();
        
        // Return with appropriate message
        if ($migrationResult['success']) {
            $message = "Module '{$moduleName}' installed successfully!";
            // Only add extra info if there's something to say
            if (!empty($migrationResult['message'])) {
                $message .= " " . $migrationResult['message'];
            }
            return back()->with('success', $message);
        } else {
            return back()->with('warning', "Module '{$moduleName}' installed but {$migrationResult['message']}. You may need to run migrations manually via: php artisan module:migrate {$moduleName}");
        }
    }

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
     * Manual migration runner (add button in UI)
     */
    public function runMigration($alias)
    {
        $module = Module::where('alias', $alias)->first();
        
        if (!$module) {
            return back()->with('error', 'Module not found in database');
        }
        
        $name = ucfirst($alias);
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
}