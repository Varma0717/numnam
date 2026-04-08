<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'   => 'required|array',
            'settings.*' => 'nullable|string|max:2000',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            SiteSetting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('status', 'Settings saved.');
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
