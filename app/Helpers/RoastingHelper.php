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
     * Get SVG icon based on roasting level
     */
    public static function getIcon($roastLevel)
    {
        return match(strtolower($roastLevel)) {
            'green' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#22c55e"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
            'light' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#eab308"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
            'medium' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#f97316"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
            'dark' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#78350f"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
            default => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#9ca3af"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>'
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
                'icon' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#22c55e"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
                'color' => 'bg-green-100 text-green-800',
                'description' => 'Biji kopi mentah/hijau yang belum di-roasting'
            ],
            'Light' => [
                'name' => 'Light',
                'icon' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#eab308"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
                'color' => 'bg-yellow-100 text-yellow-800',
                'description' => 'Roasting ringan dengan rasa asam yang menonjol'
            ],
            'Medium' => [
                'name' => 'Medium',
                'icon' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#f97316"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
                'color' => 'bg-orange-100 text-orange-800',
                'description' => 'Roasting sedang dengan keseimbangan rasa yang baik'
            ],
            'Dark' => [
                'name' => 'Dark',
                'icon' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#78350f"><path d="m19.628,19.628c-2.874,2.874-6.532,4.362-9.931,4.362-2.397,0-4.664-.744-6.438-2.26.119-.861,1.174-6.318,9.039-8.776,6.907-2.157,9.26-6.463,10.053-8.881,2.925,4.339,1.881,10.951-2.723,15.554Zm-7.926-8.582c7.864-2.457,8.919-7.914,9.039-8.776C16.451-1.397,9.272-.53,4.372,4.372-.232,8.976-1.276,15.588,1.649,19.926c.793-2.417,3.146-6.723,10.053-8.881Z"/></svg>',
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
