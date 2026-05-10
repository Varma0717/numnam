<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $tabs = ['general', 'payment', 'shipping', 'tax', 'email'];

    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'general');
        if (!in_array($activeTab, $this->tabs, true)) {
            $activeTab = 'general';
        }

        $settings = SiteSetting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings', 'activeTab'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('_tab', 'general');

        $request->validate([
            'settings'   => 'required|array',
            'settings.*' => 'nullable|string|max:5000',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('status', ucfirst($tab) . ' settings saved.');
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'       => 'required|string|max:120|unique:site_settings,key',
            'value'     => 'nullable|string|max:2000',
            'type'      => 'required|in:text,textarea,boolean,number',
            'group'     => 'nullable|string|max:60',
            'is_public' => 'nullable',
        ]);

        $data['is_public'] = $request->boolean('is_public');

        SiteSetting::create($data);

        return redirect()->route('admin.settings.index')->with('status', 'Setting created.');
    }

    public function destroy(SiteSetting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('status', 'Setting deleted.');
    }
}
