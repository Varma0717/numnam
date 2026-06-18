<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class TrackerConfigController extends Controller
{
    /**
     * Get complete tracker configuration
     * Returns all dynamic data for the mobile tracker screen
     */
    public function config()
    {
        return response()->json([
            'data' => [
                'milk_types' => $this->getMilkTypes(),
                'poop_types' => $this->getPoopTypes(),
                'feed_types' => $this->getFeedTypes(),
                'milestones' => $this->getMilestones(),
                'safety_rules' => $this->getSafetyRules(),
            ]
        ]);
    }

    /**
     * Get milk types with properties
     */
    private function getMilkTypes(): array
    {
        return [
            [
                'id' => 'formula',
                'name' => 'Formula',
                'emoji' => '🍼',
                'description' => 'Infant formula milk',
                'default_volume' => 180,
                'min_volume' => 0,
                'max_volume' => 300,
            ],
            [
                'id' => 'breast',
                'name' => 'Breast Milk',
                'emoji' => '🤱',
                'description' => 'Direct breastfeeding',
                'default_volume' => 180,
                'min_volume' => 0,
                'max_volume' => 300,
            ],
            [
                'id' => 'combination',
                'name' => 'Combination (Breast + Formula)',
                'emoji' => '🧴',
                'description' => 'Combination feeding',
                'default_volume' => 180,
                'min_volume' => 0,
                'max_volume' => 300,
            ],
        ];
    }

    /**
     * Get poop type guide data
     */
    private function getPoopTypes(): array
    {
        return [
            [
                'type' => 'Type 1',
                'emoji' => '🪨',
                'appearance' => 'Hard pellets',
                'meaning' => 'Constipation risk. Increase fluids and healthy fats.',
                'severity' => 'warning',
                'color' => '#FFCDD2',
            ],
            [
                'type' => 'Type 2',
                'emoji' => '🌰',
                'appearance' => 'Lumpy sausage',
                'meaning' => 'Mild constipation. Increase water and fruit fibre.',
                'severity' => 'caution',
                'color' => '#FFE0B2',
            ],
            [
                'type' => 'Type 3',
                'emoji' => '🌭',
                'appearance' => 'Cracked sausage',
                'meaning' => 'Borderline dry stool. Add water-rich foods.',
                'severity' => 'caution',
                'color' => '#FFF3E0',
            ],
            [
                'type' => 'Type 4',
                'emoji' => '🍌',
                'appearance' => 'Smooth soft log',
                'meaning' => 'Ideal stool consistency. Keep the same balance.',
                'severity' => 'good',
                'color' => '#C8E6C9',
            ],
            [
                'type' => 'Type 5',
                'emoji' => '💛',
                'appearance' => 'Soft blobs',
                'meaning' => 'Generally normal. Continue hydration and fibre balance.',
                'severity' => 'normal',
                'color' => '#E8F5E9',
            ],
            [
                'type' => 'Type 6',
                'emoji' => '💦',
                'appearance' => 'Mushy loose stool',
                'meaning' => 'Loose stool tendency. Pause new high-fibre foods briefly.',
                'severity' => 'warning',
                'color' => '#FFF9C4',
            ],
            [
                'type' => 'Red/Undigested',
                'emoji' => '🍅',
                'appearance' => 'Red bits or undigested food',
                'meaning' => 'Usually food pigment or immature digestion; monitor closely.',
                'severity' => 'caution',
                'color' => '#FFEBEE',
            ],
            [
                'type' => 'Green/Mucous',
                'emoji' => '💚',
                'appearance' => 'Green stool with mucous',
                'meaning' => 'Could be gut irritation. Observe hydration and symptoms.',
                'severity' => 'warning',
                'color' => '#E8F5E9',
            ],
        ];
    }

    /**
     * Get feed type configurations
     */
    private function getFeedTypes(): array
    {
        return [
            [
                'id' => 'milk',
                'label' => '🍼 Milk',
                'emoji' => '🍼',
                'description' => 'Formula or breast milk',
                'type_options' => ['Formula', 'Breast Milk', 'Combination (Breast + Formula)'],
            ],
            [
                'id' => 'solid',
                'label' => '🥣 Solids',
                'emoji' => '🥣',
                'description' => 'Purees, mashed, or finger food',
                'placeholder' => 'e.g., Carrot Puree, Banana Mash',
                'default_volume' => 100,
                'food_type_options' => ['veggie', 'fruit', 'protein', 'grain', 'dairy', 'mixed'],
                'texture_options' => ['Smooth purée', 'Thick purée', 'Mashed', 'Soft lumps', 'Chopped/Finger Food'],
                'finish_options' => ['all', 'most', 'half', 'few', 'floor', 'refused'],
            ],
            [
                'id' => 'water',
                'label' => '💧 Water',
                'emoji' => '💧',
                'description' => 'Water intake for hydration',
                'default_volume' => 30,
                'min_volume' => 0,
                'max_volume' => 100,
            ],
            [
                'id' => 'poop',
                'label' => '💩 Poop',
                'emoji' => '💩',
                'description' => 'Select the poop type to track',
            ],
        ];
    }

    /**
     * Get age-based milestones
     */
    private function getMilestones(): array
    {
        return [
            [
                'age' => 6,
                'title' => '🎉 First Spoon!',
                'description' => 'Today is about the tongue, not the tummy! If baby pushes food out — that\'s the extrusion reflex, not rejection.',
            ],
            [
                'age' => 8,
                'title' => '🌟 Bridge to Texture!',
                'description' => 'Baby is getting ~30% of energy from solids. If they seem less interested in milk, that\'s okay!',
            ],
            [
                'age' => 10,
                'title' => '🍽️ Texture Challenge Time!',
                'description' => 'Stop the blender! Moving to mashed and soft lumps now helps develop jaw muscles needed for speech.',
            ],
        ];
    }

    /**
     * Get safety rules
     */
    private function getSafetyRules(): array
    {
        return [
            [
                'icon' => '🧂',
                'title' => 'Never salt or sugar babies under 1',
                'description' => 'Their kidneys can\'t process extra sodium.',
            ],
            [
                'icon' => '⚠️',
                'title' => 'Choking hazards: whole nuts, popcorn, hard candy, grapes',
                'description' => 'Cut grapes lengthwise into 4 pieces.',
            ],
            [
                'icon' => '🍯',
                'title' => 'Never honey before 1 year',
                'description' => 'Botulism risk. Use mashed fruit or date paste instead.',
            ],
        ];
    }
}
