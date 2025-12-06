<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class LinkPermissionsToModulesSeeder extends Seeder
{
    public function run(): void
    {
        // Get all modules
        $modules = Module::all()->keyBy('alias');

        // Get all permissions
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            // Extract module alias from permission name (e.g., "book.list.read" -> "book")
            $parts = explode('.', $permission->name);
            $moduleAlias = $parts[0] ?? null;
            $actionName = $this->getActionName($parts);

            if ($moduleAlias && isset($modules[$moduleAlias])) {
                $permission->update([
                    'module_id' => $modules[$moduleAlias]->id,
                    'action_name' => $actionName,
                ]);
            }
        }

        $this->command->info('Permissions linked to modules successfully!');
    }

    /**
     * Get human-readable action name
     */
    private function getActionName(array $parts): string
    {
        $actionSlug = end($parts);
        
        $actionNames = [
            'read' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'update' => 'Update',
            'delete' => 'Delete',
            'export' => 'Export',
            'import' => 'Import',
            'write' => 'Write',
        ];

        return $actionNames[$actionSlug] ?? ucfirst($actionSlug);
    }
}