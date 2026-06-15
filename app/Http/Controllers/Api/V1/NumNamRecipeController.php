<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BabyProfile;
use App\Models\NumNamRecipe;
use Illuminate\Http\Request;

class NumNamRecipeController extends Controller
{
    /**
     * Get recipes for baby's age
     */
    public function index(Request $request)
    {
        $ageMonths = 6; // Default age

        // If user is authenticated, get their baby's age
        if ($request->user()) {
            $profile = BabyProfile::where('user_id', $request->user()->id)->first();
            if ($profile) {
                $ageMonths = $profile->age_months;
            }
        }

        $recipes = NumNamRecipe::getForAge($ageMonths);

        return response()->json([
            'data' => $recipes,
        ]);
    }

    /**
     * Get single recipe
     */
    public function show(NumNamRecipe $recipe)
    {
        return response()->json($recipe);
    }

    /**
     * Toggle like on recipe
     */
    public function toggleLike(Request $request, NumNamRecipe $recipe)
    {
        $recipe->toggleLike($request->user()->id);
        $recipe->refresh();

        return response()->json([
            'data' => [
                'hearts_count' => $recipe->hearts_count,
                'liked' => $recipe->likedByUsers()->where('user_id', $request->user()->id)->exists(),
            ]
        ]);
    }

    /**
     * Get recipes by food type
     */
    public function byType(Request $request, $foodType)
    {
        $validated = $request->validate([
            'food_type' => 'required|in:veggie,fruit,protein,grain,dairy,mixed',
        ]);

        $ageMonths = 6; // Default age

        // If user is authenticated, get their baby's age
        if ($request->user()) {
            $profile = BabyProfile::where('user_id', $request->user()->id)->first();
            if ($profile) {
                $ageMonths = $profile->age_months;
            }
        }

        $recipes = NumNamRecipe::where('min_age_months', '<=', $ageMonths)
            ->where('food_type', $foodType)
            ->orderBy('hearts_count', 'desc')
            ->get();

        return response()->json([
            'food_type' => $foodType,
            'recipes' => $recipes,
        ]);
    }
}
