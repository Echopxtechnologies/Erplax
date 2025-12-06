<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RegisterCoreModules extends Command
{
    protected $signature = 'core:register {--service= : Register specific service only}';

    protected $description = 'Register all core module services from app/Services/Admin folder';

    protected array $exclude = [
        'AdminService.php',
        'CoreMenuService.php',
    ];

    public function handle(): int
    {
        $this->info('🔄 Registering core modules...');
        $this->newLine();

        $servicesPath = app_path('Services/Admin');

        if (!File::isDirectory($servicesPath)) {
            $this->error("Services folder not found: {$servicesPath}");
            return Command::FAILURE;
        }

        $files = File::files($servicesPath);
        $registered = 0;
        $failed = 0;

        $specificService = $this->option('service');

        foreach ($files as $file) {
            $filename = $file->getFilename();

            if (in_array($filename, $this->exclude)) {
                continue;
            }

            if (!str_ends_with($filename, 'Service.php')) {
                continue;
            }

            if ($specificService && $filename !== $specificService . 'Service.php') {
                continue;
            }

            $className = 'App\\Services\\Admin\\' . str_replace('.php', '', $filename);

            if (!class_exists($className)) {
                $this->warn("  ⚠ Class not found: {$className}");
                $failed++;
                continue;
            }

            if (!method_exists($className, 'register')) {
                $this->warn("  ⚠ No register() method: {$filename}");
                continue;
            }

            try {
                $module = $className::register();
                
                $this->line("  ✓ <info>{$module->name}</info> registered (alias: {$module->alias})");
                
                if (method_exists($className, 'menu')) {
                    $menu = $className::menu();
                    if (!empty($menu['route'])) {
                        $this->line("    └─ Menu: {$menu['title']} → {$menu['route']}");
                    } else {
                        $this->line("    └─ Menu: {$menu['title']} (panel)");
                    }
                }
                
                $registered++;
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$filename} - {$e->getMessage()}");
                $failed++;
            }
        }

        $this->newLine();
        
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
}