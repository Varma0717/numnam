<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BabyProfile;
use App\Models\FeedLog;
use Illuminate\Http\Request;

class NumNamFeedLogController extends Controller
{
    /**
     * Store a new feed log entry
     */
    public function store(Request $request)
    {
        $profile = BabyProfile::where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'type' => 'required|in:milk,solid,water,poop',
            'volume_ml' => 'required_if:type,milk,solid,water|integer|min:1',
            'milk_type' => 'required_if:type,milk|in:breast,formula',
            'food_name' => 'required_if:type,solid|string|max:100',
            'food_type' => 'required_if:type,solid|in:veggie,fruit,protein,grain,dairy,mixed',
            'texture' => 'required_if:type,solid|string|max:50',
            'finish_level' => 'required_if:type,solid|in:all,most,half,few,floor,refused',
            'poop_type' => 'required_if:type,poop|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['baby_profile_id'] = $profile->id;
        $validated['logged_at'] = now();

        $log = FeedLog::create($validated);

        return response()->json([
            'message' => 'Log entry created successfully',
            'data' => $log,
        ], 201);
    }

    /**
     * Get today's logs
     */
    public function todayLogs(Request $request)
    {
        $profile = BabyProfile::where('user_id', $request->user()->id)->firstOrFail();
        $logs = FeedLog::getTodayLogs($profile->id);

        return response()->json([
            'data' => $logs,
        ]);
    }

    /**
     * Delete a log entry
     */
    public function destroy(Request $request, FeedLog $feedLog)
    {
        $profile = BabyProfile::where('user_id', $request->user()->id)->firstOrFail();

        if ($feedLog->baby_profile_id !== $profile->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $feedLog->delete();

        return response()->json(['message' => 'Log entry deleted']);
    }

    /**
     * Clear all today's logs
     */
    public function clearToday(Request $request)
    {
        $profile = BabyProfile::where('user_id', $request->user()->id)->firstOrFail();

        FeedLog::where('baby_profile_id', $profile->id)
            ->whereDate('logged_at', today())
            ->delete();

        return response()->json(['message' => 'Today\'s logs cleared']);
    }
}
