<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Health & Fitness',  'slug' => 'health-fitness',   'color' => '#22c55e', 'icon' => 'bi-heart-pulse'],
            ['name' => 'Career & Work',      'slug' => 'career-work',       'color' => '#6366f1', 'icon' => 'bi-briefcase'],
            ['name' => 'Education',          'slug' => 'education',         'color' => '#06b6d4', 'icon' => 'bi-book'],
            ['name' => 'Finance',            'slug' => 'finance',           'color' => '#f59e0b', 'icon' => 'bi-currency-rupee'],
            ['name' => 'Personal Growth',   'slug' => 'personal-growth',   'color' => '#8b5cf6', 'icon' => 'bi-person-up'],
            ['name' => 'Relationships',      'slug' => 'relationships',     'color' => '#ec4899', 'icon' => 'bi-people'],
            ['name' => 'Travel',             'slug' => 'travel',            'color' => '#14b8a6', 'icon' => 'bi-airplane'],
            ['name' => 'Hobbies',            'slug' => 'hobbies',           'color' => '#f97316', 'icon' => 'bi-palette'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
