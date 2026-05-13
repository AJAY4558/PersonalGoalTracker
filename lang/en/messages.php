<?php

/*
|--------------------------------------------------------------------------
| English Language File — messages.php
|--------------------------------------------------------------------------
| All UI strings used in Blade templates via __('messages.key') or trans()
*/

return [
    // Navigation
    'nav' => [
        'dashboard'  => 'Dashboard',
        'my_goals'   => 'My Goals',
        'new_goal'   => 'New Goal',
        'profile'    => 'Profile',
        'logout'     => 'Logout',
        'login'      => 'Login',
        'register'   => 'Register',
        'admin'      => 'Admin',
    ],

    // Dashboard
    'dashboard' => [
        'title'           => 'Dashboard',
        'welcome'         => 'Welcome back, :name!',
        'total_goals'     => 'Total Goals',
        'completed'       => 'Completed',
        'pending'         => 'Pending',
        'in_progress'     => 'In Progress',
        'completion_rate' => 'Completion Rate',
        'upcoming'        => 'Upcoming Deadlines',
        'recent'          => 'Recent Goals',
        'no_goals'        => 'No goals yet. Create your first goal!',
    ],

    // Goals
    'goals' => [
        'title'       => 'My Goals',
        'create'      => 'Create Goal',
        'edit'        => 'Edit Goal',
        'view'        => 'View Goal',
        'delete'      => 'Delete Goal',
        'no_goals'    => 'No goals found.',
        'search'      => 'Search goals...',
        'filter'      => 'Filter',
        'all_status'  => 'All Statuses',
        'all_cat'     => 'All Categories',
    ],

    // Goal fields
    'goal' => [
        'title'       => 'Title',
        'description' => 'Description',
        'deadline'    => 'Deadline',
        'status'      => 'Status',
        'progress'    => 'Progress',
        'priority'    => 'Priority',
        'category'    => 'Category',
        'image'       => 'Attachment / Image',
        'created_at'  => 'Created',
        'updated_at'  => 'Updated',
    ],

    // Status labels
    'status' => [
        'pending'     => 'Pending',
        'in_progress' => 'In Progress',
        'completed'   => 'Completed',
        'cancelled'   => 'Cancelled',
    ],

    // Priority labels
    'priority' => [
        'low'      => 'Low',
        'medium'   => 'Medium',
        'high'     => 'High',
        'critical' => 'Critical',
    ],

    // Auth
    'auth' => [
        'login'          => 'Login',
        'register'       => 'Register',
        'logout'         => 'Logout',
        'name'           => 'Full Name',
        'email'          => 'Email Address',
        'password'       => 'Password',
        'confirm_pass'   => 'Confirm Password',
        'remember'       => 'Remember Me',
        'forgot'         => 'Forgot Password?',
        'no_account'     => 'Don\'t have an account?',
        'have_account'   => 'Already have an account?',
    ],

    // Common
    'common' => [
        'save'    => 'Save',
        'update'  => 'Update',
        'cancel'  => 'Cancel',
        'delete'  => 'Delete',
        'back'    => 'Back',
        'confirm' => 'Are you sure?',
        'yes'     => 'Yes',
        'no'      => 'No',
        'actions' => 'Actions',
        'days_left'   => ':n day(s) left',
        'overdue'     => 'Overdue!',
        'no_deadline' => 'No deadline',
    ],

    // Language switcher
    'language' => [
        'en' => 'English',
        'hi' => 'हिन्दी',
        'switch' => 'Switch Language',
    ],
];
