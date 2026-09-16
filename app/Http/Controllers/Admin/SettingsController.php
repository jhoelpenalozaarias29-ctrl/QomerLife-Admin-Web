<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\SettingsRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsRepositoryInterface $settingsRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
        private readonly \App\Repositories\Contracts\ProductRepositoryInterface $productRepository
    ) {
    }

    public function index()
    {
        $settings = [
            'maxDailyTransactions' => 50,
            'systemActive' => true,
        ];

        $productos = [];

        try {
            $fetchedSettings = $this->settingsRepository->all();
            if ($fetchedSettings) {
                $settings = array_merge($settings, $fetchedSettings);
            }
            $productos = $this->productRepository->all();
        } catch (\Exception $e) {
            Log::error('Error fetching settings/products: ' . $e->getMessage());
        }

        return view('admin.settings.index', [
            'breadcrumbs' => ['Dashboard' => route('admin.dashboard'), 'Settings' => null],
            'settings' => $settings,
            'productos' => $productos
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'maxDailyTransactions' => 'required|integer|min:1',
            'systemActive' => 'boolean',
        ]);

        // Cast boolean if checkbox is not present it might be false
        $validated['systemActive'] = $request->has('systemActive');

        try {
            $oldSettings = $this->settingsRepository->all();
            
            $this->settingsRepository->setMany($validated);

            $this->auditLogRepository->log(
                action: 'UPDATE_SETTINGS',
                description: "Updated system settings",
                userId: auth()->id() ?? 'system',
                metadata: [
                    'resource' => 'settings',
                    'resourceId' => 'global',
                    'oldData' => $oldSettings ?: [],
                    'newData' => $validated,
                ]
            );

            return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating settings.');
        }
    }
}
