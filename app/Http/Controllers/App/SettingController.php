<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $general = $this->settingService->getGroup('general');
        $transactionPrefix = $this->settingService->getGroup('transaction_prefix');

        return Inertia::render('core/settings/Index', [
            'settings' => [
                'general' => $general,
                'transaction_prefix' => $transactionPrefix,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'general' => 'required|array',
            'general.app_name' => 'required|string|max:255',
            'general.language' => 'required|string|in:id,en',
            'general.timezone' => 'required|string|timezone',
            'general.currency' => 'required|string|size:3',
            'general.currency_locale' => 'required|string|max:10',
            'general.date_format' => 'required|string|max:20',
            'general.decimal_precision' => 'required|integer|min:0|max:4',
            'general.default_tax_rate' => 'required|numeric|min:0|max:100',
            
            'transaction_prefix' => 'required|array',
            'transaction_prefix.purchase_order' => 'required|string|max:10|alpha_num',
            'transaction_prefix.sales_manual' => 'required|string|max:10|alpha_num',
            'transaction_prefix.sales_pos' => 'required|string|max:10|alpha_num',
            'transaction_prefix.trade_in' => 'required|string|max:10|alpha_num',
        ]);

        $this->settingService->setGroup('general', $validated['general']);
        $this->settingService->setGroup('transaction_prefix', $validated['transaction_prefix']);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
