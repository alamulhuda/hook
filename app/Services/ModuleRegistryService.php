<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ModuleRegistryService
{
    protected string $statusesPath;

    public function __construct()
    {
        $this->statusesPath = storage_path('app/modules_statuses.json');
    }

    /**
     * Get all raw stored module statuses.
     */
    public function getStatuses(): array
    {
        if (!File::exists($this->statusesPath)) {
            return [];
        }

        $content = File::get($this->statusesPath);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Save the statuses array to json storage.
     */
    public function saveStatuses(array $statuses): void
    {
        File::ensureDirectoryExists(dirname($this->statusesPath));
        File::put($this->statusesPath, json_encode($statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Check if a module is enabled by ID or directory name (case insensitive).
     */
    public function isModuleEnabled(string $moduleNameOrId): bool
    {
        $statuses = $this->getStatuses();
        $key = strtolower($moduleNameOrId);

        // Also check exact directory name matches if different
        if (isset($statuses[$moduleNameOrId])) {
            return (bool) $statuses[$moduleNameOrId];
        }

        if (isset($statuses[$key])) {
            return (bool) $statuses[$key];
        }

        // Default to enabled if not explicitly disabled
        return true;
    }

    /**
     * Enable a module by ID or directory name.
     */
    public function enableModule(string $id): void
    {
        $statuses = $this->getStatuses();
        $statuses[strtolower($id)] = true;
        $this->saveStatuses($statuses);
    }

    /**
     * Disable a module by ID or directory name.
     */
    public function disableModule(string $id): void
    {
        $statuses = $this->getStatuses();
        $statuses[strtolower($id)] = false;
        $this->saveStatuses($statuses);
    }

    /**
     * Get all modules with their manifest details and active status.
     */
    public function getAllModules(): array
    {
        $modulesPath = app_path('Modules');
        if (!is_dir($modulesPath)) {
            return [];
        }

        $statuses = $this->getStatuses();
        $modules = [];

        foreach (scandir($modulesPath) as $dir) {
            if ($dir === '.' || $dir === '..' || !is_dir($modulesPath . '/' . $dir)) {
                continue;
            }

            $manifestPath = $modulesPath . '/' . $dir . '/module.json';
            $manifest = [
                'id' => strtolower($dir),
                'name' => $dir,
                'version' => '1.0.0',
                'publisher' => 'Hook ERP Core Team',
                'description' => 'Module ' . $dir,
                'changelog' => []
            ];

            if (File::exists($manifestPath)) {
                $content = File::get($manifestPath);
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $manifest = array_merge($manifest, $decoded);
                }
            }

            $id = strtolower($manifest['id'] ?? $dir);
            $enabled = true;
            if (isset($statuses[$id])) {
                $enabled = (bool) $statuses[$id];
            } elseif (isset($statuses[$dir])) {
                $enabled = (bool) $statuses[$dir];
            } elseif (isset($statuses[strtolower($dir)])) {
                $enabled = (bool) $statuses[strtolower($dir)];
            }

            $manifest['directory'] = $dir;
            $manifest['enabled'] = $enabled;

            $modules[] = $manifest;
        }

        return $modules;
    }

    /**
     * Get IDs of all enabled modules (both kebab-case id and directory lowercase name for reliable matching).
     */
    public function getEnabledModuleIds(): array
    {
        $all = $this->getAllModules();
        $enabledIds = [];

        foreach ($all as $mod) {
            if (!empty($mod['enabled'])) {
                $enabledIds[] = strtolower($mod['id']);
                $enabledIds[] = strtolower($mod['directory']);
                // Convert directory like ServiceRepair to service-repair
                $kebab = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $mod['directory']));
                $enabledIds[] = $kebab;
            }
        }

        return array_values(array_unique($enabledIds));
    }
}
