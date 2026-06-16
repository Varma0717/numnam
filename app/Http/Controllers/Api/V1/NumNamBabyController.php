<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BabyProfile;
use App\Models\FeedLog;
use Illuminate\Http\Request;

class NumNamBabyController extends Controller
{
    /**
     * Get or create baby profile
     */
    public function profile(Request $request)
    {
        $profile = BabyProfile::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['baby_name' => 'My Baby', 'age_months' => 6, 'milk_type' => 'breast']
        );

        return response()->json([
            'data' => $profile,
        ]);
    }

    /**
     * Update baby profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'baby_name' => 'required|string|max:100',
            'age_months' => 'required|integer|min:5|max:36',
            'weight_kg' => 'nullable|numeric|min:3|max:20',
            'milk_type' => 'required|in:breast,formula,combination',
        ]);

        $profile = BabyProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $profile,
        ]);
    }

    /**
     * Get today's dashboard summary
     */
    public function dashboardSummary(Request $request)
    {
        $profile = BabyProfile::where('user_id', $request->user()->id)->first();

        // Auto-create profile if it doesn't exist
        if (!$profile) {
            $profile = BabyProfile::create([
                'user_id' => $request->user()->id,
                'name' => 'My Baby',
                'date_of_birth' => now()->subMonths(6),
            ]);
        }

        $logs = FeedLog::getTodayLogs($profile->id);

        $milk = $logs->where('type', 'milk')->sum('volume_ml');
        $solid = $logs->where('type', 'solid')->sum('volume_ml');
        $water = $logs->where('type', 'water')->sum('volume_ml');
        $lastPoop = $logs->where('type', 'poop')->latest('logged_at')->first();

        $mkCal = $logs->where('type', 'milk')->sum(function ($log) {
            return $log->calories;
        });

        $skCal = $logs->where('type', 'solid')->sum(function ($log) {
            return $log->calories;
        });

        $totalCal = $mkCal + $skCal;

        return response()->json([
            'profile' => $profile,
            'summary' => [
                'milk_ml' => $milk,
                'solid_ml' => $solid,
                'water_ml' => $water,
                'last_poop_type' => $lastPoop?->poop_type,
                'milk_calories' => $mkCal,
                'solid_calories' => $skCal,
                'total_calories' => $totalCal,
                'daily_calorie_need' => $profile->daily_calorie_need,
            ],
            'logs' => $logs,
        ]);
    }
}
