<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Demo user
        $user = User::firstOrCreate(
            ['email' => 'demo@goaltracker.com'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('password123'),
                'locale'   => 'en',
            ]
        );

        // Sample goals for demo user
        $categories = Category::all()->keyBy('slug');

        $goals = [
            [
                'title'       => 'Learn Laravel Framework',
                'description' => 'Complete the Laravel MVC course and build a full-stack project for BTech submission.',
                'deadline'    => now()->addDays(30)->format('Y-m-d'),
                'status'      => 'in_progress',
                'progress'    => 75,
                'priority'    => 'high',
                'category_id' => $categories['education']->id ?? null,
            ],
            [
                'title'       => 'Exercise 5 Days a Week',
                'description' => 'Build a consistent workout routine — gym or home exercises every weekday.',
                'deadline'    => now()->addDays(60)->format('Y-m-d'),
                'status'      => 'in_progress',
                'progress'    => 40,
                'priority'    => 'medium',
                'category_id' => $categories['health-fitness']->id ?? null,
            ],
            [
                'title'       => 'Save ₹50,000 Emergency Fund',
                'description' => 'Build an emergency fund of 50,000 rupees over the next 6 months.',
                'deadline'    => now()->addMonths(6)->format('Y-m-d'),
                'status'      => 'pending',
                'progress'    => 20,
                'priority'    => 'critical',
                'category_id' => $categories['finance']->id ?? null,
            ],
            [
                'title'       => 'Read 12 Books This Year',
                'description' => 'Read at least one book per month across different genres.',
                'deadline'    => now()->endOfYear()->format('Y-m-d'),
                'status'      => 'in_progress',
                'progress'    => 50,
                'priority'    => 'low',
                'category_id' => $categories['personal-growth']->id ?? null,
            ],
            [
                'title'       => 'Get AWS Cloud Practitioner Certification',
                'description' => 'Study for and pass the AWS Certified Cloud Practitioner exam.',
                'deadline'    => now()->addDays(90)->format('Y-m-d'),
                'status'      => 'pending',
                'progress'    => 10,
                'priority'    => 'high',
                'category_id' => $categories['career-work']->id ?? null,
            ],
            [
                'title'       => 'Complete Laravel MVC Project',
                'description' => 'Submit the Personal Goal Tracker as BTech final project.',
                'deadline'    => now()->addDays(7)->format('Y-m-d'),
                'status'      => 'completed',
                'progress'    => 100,
                'priority'    => 'critical',
                'category_id' => $categories['education']->id ?? null,
                'completed_at' => now(),
            ],
        ];

        foreach ($goals as $goalData) {
            Goal::firstOrCreate(
                ['user_id' => $user->id, 'title' => $goalData['title']],
                array_merge($goalData, ['user_id' => $user->id])
            );
        }
    }
}
