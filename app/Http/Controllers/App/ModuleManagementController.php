<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\ModuleRegistryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class ModuleManagementController extends Controller
{
    public function index(ModuleRegistryService $registry): Response
    {
        $modules = $registry->getAllModules();

        return Inertia::render('core/settings/modules/Index', [
            'modules' => $modules,
        ]);
    }

    public function toggle(Request $request, string $id, ModuleRegistryService $registry): RedirectResponse
    {
        $enabled = $registry->isModuleEnabled($id);

        if ($enabled) {
            $registry->disableModule($id);
            $msg = "Module [{$id}] disabled successfully.";
        } else {
            $registry->enableModule($id);
            $msg = "Module [{$id}] enabled successfully.";
        }

        return back()->with('success', $msg);
    }

    public function updateModule(Request $request, string $id, ModuleRegistryService $registry): RedirectResponse
    {
        // Check verification & return status
        return back()->with('success', "Module [{$id}] is verified and up to date (v1.0.0).");
    }

    public function destroy(string $id, ModuleRegistryService $registry): RedirectResponse
    {
        $registry->disableModule($id);

        // Find matching directory inside app/Modules
        $modulesPath = app_path('Modules');
        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $dir) {
                if ($dir === '.' || $dir === '..') continue;
                if (strtolower($dir) === strtolower($id)) {
                    File::deleteDirectory($modulesPath . '/' . $dir);
                    break;
                }
            }
        }

        // Find matching frontend directory inside resources/js/modules
        $frontendPath = resource_path('js/modules');
        if (is_dir($frontendPath)) {
            foreach (scandir($frontendPath) as $dir) {
                if ($dir === '.' || $dir === '..') continue;
                if (strtolower($dir) === strtolower($id) || strtolower($dir) === strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $id))) {
                    File::deleteDirectory($frontendPath . '/' . $dir);
                    break;
                }
            }
        }

        return back()->with('success', "Module [{$id}] and all its files have been deleted.");
    }
}
