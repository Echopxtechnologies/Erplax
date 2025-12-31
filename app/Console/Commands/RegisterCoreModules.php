<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RegisterCoreModules extends Command
{
    protected $signature = 'core:register {module?} {--core-only : Register only core modules} {--modules-only : Register only nWidart modules}';

    protected $description = 'Register core modules (app/Services/Admin) and nWidart modules (Modules/*/Services)';

    protected array $exclude = [
        'AdminService.php',
        'CoreMenuService.php',
    ];

    public function handle(): int
    {
        $this->info('🔄 Registering modules...');
        $this->newLine();

        $targetModule = $this->argument('module');
        $coreOnly = $this->option('core-only');
        $modulesOnly = $this->option('modules-only');

        $registered = 0;
        $failed = 0;

        // ─────────────────────────────────────────────
        // 1. Core Modules (app/Services/Admin)
        // ─────────────────────────────────────────────
        if (!$modulesOnly) {
            $this->line('<fg=cyan>── Core Modules (app/Services/Admin) ──</>');
            
            $servicesPath = app_path('Services/Admin');

            if (File::isDirectory($servicesPath)) {
                foreach (File::files($servicesPath) as $file) {
                    $filename = $file->getFilename();

                    if (in_array($filename, $this->exclude)) {
                        continue;
                    }

                    if (!str_ends_with($filename, 'Service.php')) {
                        continue;
                    }

                    // Filter by module name if provided
                    if ($targetModule) {
                        $serviceName = str_replace('Service.php', '', $filename);
                        if (strtolower($serviceName) !== strtolower($targetModule)) {
                            continue;
                        }
                    }

                    $className = 'App\\Services\\Admin\\' . str_replace('.php', '', $filename);
                    $result = $this->registerService($className, $filename);

                    if ($result === true) $registered++;
                    elseif ($result === false) $failed++;
                }
            } else {
                $this->warn("  Core services folder not found: {$servicesPath}");
            }

            $this->newLine();
        }

        // ─────────────────────────────────────────────
        // 2. nWidart Modules (Modules/*/Services)
        // ─────────────────────────────────────────────
        if (!$coreOnly) {
            $this->line('<fg=cyan>── nWidart Modules (Modules/*/Services) ──</>');

            $modulesPath = base_path('Modules');

            if (File::isDirectory($modulesPath)) {
                $foundAny = false;

                foreach (File::directories($modulesPath) as $moduleDir) {
                    $moduleName = basename($moduleDir);
                    $servicesPath = $moduleDir . '/Services';

                    // Filter by module name if provided
                    if ($targetModule && strtolower($moduleName) !== strtolower($targetModule)) {
                        continue;
                    }

                    if (!File::isDirectory($servicesPath)) {
                        continue;
                    }

                    foreach (File::files($servicesPath) as $file) {
                        $filename = $file->getFilename();

                        if (!str_ends_with($filename, 'Service.php')) {
                            continue;
                        }

                        $foundAny = true;
                        $className = "Modules\\{$moduleName}\\Services\\" . str_replace('.php', '', $filename);
                        $result = $this->registerService($className, "{$moduleName}/{$filename}");

                        if ($result === true) $registered++;
                        elseif ($result === false) $failed++;
                    }
                }

                if (!$foundAny && !$targetModule) {
                    $this->line('  <fg=gray>No module services found</fg>');
                }
            } else {
                $this->line('  <fg=gray>Modules folder not found</fg>');
            }

            $this->newLine();
        }

        // ─────────────────────────────────────────────
        // Summary
        // ─────────────────────────────────────────────
        if ($registered > 0) {
            $this->info("✅ Registered {$registered} module(s)");
        }

        if ($failed > 0) {
            $this->warn("⚠ Failed: {$failed}");
        }

        if ($registered === 0 && $failed === 0) {
            $this->warn("No services found to register.");
        }

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Register a single service
     * 
     * @return bool|null true=success, false=failed, null=skipped
     */
    protected function registerService(string $className, string $displayName): ?bool
    {
        if (!class_exists($className)) {
            $this->warn("  ⚠ Class not found: {$displayName}");
            return false;
        }

        if (!method_exists($className, 'register')) {
            $this->line("  <fg=gray>⊘ No register() method: {$displayName}</>");
            return null;
        }

        try {
            $module = $className::register();
            $this->line("  <fg=green>✓</> {$module->name} <fg=gray>(alias: {$module->alias})</>");
            return true;
        } catch (\Exception $e) {
            $this->error("  ✗ Failed: {$displayName} - " . $e->getMessage());
            return false;
        }
    }
}