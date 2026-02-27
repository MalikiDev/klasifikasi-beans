<?php

namespace App\Helpers;

class RoastingHelper
{
    /**
     * Get badge color class based on roasting level
     */
    public static function getBadgeColor($roastLevel)
    {
        return match(strtolower($roastLevel)) {
            'green' => 'bg-green-100 text-green-800',
            'light' => 'bg-yellow-100 text-yellow-800',
            'medium' => 'bg-orange-100 text-orange-800',
            'dark' => 'bg-amber-900 text-white',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get emoji icon based on roasting level
     */
    public static function getIcon($roastLevel)
    {
        return match(strtolower($roastLevel)) {
            'green' => '🟢',
            'light' => '🟡',
            'medium' => '🟠',
            'dark' => '🟤',
            default => '⚪'
        };
    }

    /**
     * Get description based on roasting level
     */
    public static function getDescription($roastLevel)
    {
        return match(strtolower($roastLevel)) {
            'green' => 'Biji kopi mentah/hijau yang belum di-roasting',
            'light' => 'Roasting ringan dengan rasa asam yang menonjol',
            'medium' => 'Roasting sedang dengan keseimbangan rasa yang baik',
            'dark' => 'Roasting gelap dengan rasa pahit dan body yang kuat',
            default => 'Tingkat roasting tidak diketahui'
        };
    }

    /**
     * Get all roasting levels
     */
    public static function getAllLevels()
    {
        return [
            'Green' => [
                'name' => 'Green',
                'icon' => '🟢',
                'color' => 'bg-green-100 text-green-800',
                'description' => 'Biji kopi mentah/hijau yang belum di-roasting'
            ],
            'Light' => [
                'name' => 'Light',
                'icon' => '🟡',
                'color' => 'bg-yellow-100 text-yellow-800',
                'description' => 'Roasting ringan dengan rasa asam yang menonjol'
            ],
            'Medium' => [
                'name' => 'Medium',
                'icon' => '🟠',
                'color' => 'bg-orange-100 text-orange-800',
                'description' => 'Roasting sedang dengan keseimbangan rasa yang baik'
            ],
            'Dark' => [
                'name' => 'Dark',
                'icon' => '🟤',
                'color' => 'bg-amber-900 text-white',
                'description' => 'Roasting gelap dengan rasa pahit dan body yang kuat'
            ]
        ];
    }

    /**
     * Get confidence level description
     */
    public static function getConfidenceLevel($confidence)
    {
        if ($confidence >= 90) {
            return ['level' => 'Sangat Tinggi', 'color' => 'text-green-600'];
        } elseif ($confidence >= 75) {
            return ['level' => 'Tinggi', 'color' => 'text-blue-600'];
        } elseif ($confidence >= 60) {
            return ['level' => 'Sedang', 'color' => 'text-yellow-600'];
        } else {
            return ['level' => 'Rendah', 'color' => 'text-red-600'];
        }
    }
}
