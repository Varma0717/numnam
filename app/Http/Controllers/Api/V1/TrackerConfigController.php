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
                'min_volume' => 60,
                'max_volume' => 300,
            ],
            [
                'id' => 'breast',
                'name' => 'Breast Milk',
                'emoji' => '🤱',
                'description' => 'Direct breastfeeding',
                'default_volume' => 20,
                'min_volume' => 10,
                'max_volume' => 60,
            ],
            [
                'id' => 'expressed',
                'name' => 'Expressed Milk',
                'emoji' => '🍶',
                'description' => 'Pumped breast milk',
                'default_volume' => 150,
                'min_volume' => 60,
                'max_volume' => 250,
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
                'appearance' => 'Hard, small balls',
                'meaning' => 'Constipation risk. Increase fluids and healthy fats.',
                'severity' => 'warning',
                'color' => '#FFCDD2', // red[100]
            ],
            [
                'type' => 'Type 2',
                'emoji' => '🔗',
                'appearance' => 'Lumpy, connected balls',
                'meaning' => 'Mild constipation. Increase water & fibre slightly.',
                'severity' => 'caution',
                'color' => '#FFE0B2', // orange[100]
            ],
            [
                'type' => 'Type 4',
                'emoji' => '✨',
                'appearance' => 'Smooth, soft log',
                'meaning' => 'Perfect! Fibre & fluid balance is ideal.',
                'severity' => 'good',
                'color' => '#C8E6C9', // green[100]
            ],
            [
                'type' => 'Type 6',
                'emoji' => '⚡',
                'appearance' => 'Fluffy, mushy pieces',
                'meaning' => 'Loose/Diarrhoea risk. Reduce high-fibre foods.',
                'severity' => 'warning',
                'color' => '#FFF9C4', // yellow[100]
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
                'type_options' => ['Formula', 'Breast Milk', 'Expressed Milk'],
            ],
            [
                'id' => 'solid',
                'label' => '🥣 Solids',
                'emoji' => '🥣',
                'description' => 'What food did baby eat?',
                'placeholder' => 'e.g., Carrot purée',
                'default_volume' => 100,
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
