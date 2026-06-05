<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ToolsController extends Controller
{
    /**
     * Show tools hub/index
     */
    public function index()
    {
        $tools = [
            [
                'id' => 'numnam-tracker',
                'name' => 'NumNam Weaning Tracker',
                'description' => 'Track your baby\'s feeding journey with personalized insights, recipes, and developmental guidance.',
                'icon' => '🍼',
                'route' => 'store.tools.numnam',
                'category' => 'Nutrition'
            ]
        ];

        return view('store.tools.index', compact('tools'));
    }

    /**
     * Show NumNam tracker tool
     */
    public function numnam()
    {
        return view('store.tools.numnam');
    }
}
