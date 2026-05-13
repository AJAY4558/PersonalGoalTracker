<?php

/*
|--------------------------------------------------------------------------
| Hindi Language File — messages.php
|--------------------------------------------------------------------------
| हिन्दी अनुवाद — सभी UI स्ट्रिंग्स
*/

return [
    // Navigation
    'nav' => [
        'dashboard'  => 'डैशबोर्ड',
        'my_goals'   => 'मेरे लक्ष्य',
        'new_goal'   => 'नया लक्ष्य',
        'profile'    => 'प्रोफ़ाइल',
        'logout'     => 'लॉग आउट',
        'login'      => 'लॉग इन',
        'register'   => 'पंजीकरण',
        'admin'      => 'एडमिन',
    ],

    // Dashboard
    'dashboard' => [
        'title'           => 'डैशबोर्ड',
        'welcome'         => 'स्वागत है, :name!',
        'total_goals'     => 'कुल लक्ष्य',
        'completed'       => 'पूर्ण',
        'pending'         => 'लंबित',
        'in_progress'     => 'प्रगति में',
        'completion_rate' => 'पूर्णता दर',
        'upcoming'        => 'आगामी समय-सीमाएं',
        'recent'          => 'हाल के लक्ष्य',
        'no_goals'        => 'अभी कोई लक्ष्य नहीं। अपना पहला लक्ष्य बनाएं!',
    ],

    // Goals
    'goals' => [
        'title'       => 'मेरे लक्ष्य',
        'create'      => 'लक्ष्य बनाएं',
        'edit'        => 'लक्ष्य संपादित करें',
        'view'        => 'लक्ष्य देखें',
        'delete'      => 'लक्ष्य हटाएं',
        'no_goals'    => 'कोई लक्ष्य नहीं मिला।',
        'search'      => 'लक्ष्य खोजें...',
        'filter'      => 'फ़िल्टर',
        'all_status'  => 'सभी स्थितियां',
        'all_cat'     => 'सभी श्रेणियां',
    ],

    // Goal fields
    'goal' => [
        'title'       => 'शीर्षक',
        'description' => 'विवरण',
        'deadline'    => 'समय-सीमा',
        'status'      => 'स्थिति',
        'progress'    => 'प्रगति',
        'priority'    => 'प्राथमिकता',
        'category'    => 'श्रेणी',
        'image'       => 'अटैचमेंट / छवि',
        'created_at'  => 'बनाया गया',
        'updated_at'  => 'अपडेट किया गया',
    ],

    // Status labels
    'status' => [
        'pending'     => 'लंबित',
        'in_progress' => 'प्रगति में',
        'completed'   => 'पूर्ण',
        'cancelled'   => 'रद्द',
    ],

    // Priority labels
    'priority' => [
        'low'      => 'कम',
        'medium'   => 'मध्यम',
        'high'     => 'उच्च',
        'critical' => 'महत्वपूर्ण',
    ],

    // Auth
    'auth' => [
        'login'          => 'लॉग इन',
        'register'       => 'पंजीकरण',
        'logout'         => 'लॉग आउट',
        'name'           => 'पूरा नाम',
        'email'          => 'ईमेल पता',
        'password'       => 'पासवर्ड',
        'confirm_pass'   => 'पासवर्ड की पुष्टि करें',
        'remember'       => 'मुझे याद रखें',
        'forgot'         => 'पासवर्ड भूल गए?',
        'no_account'     => 'खाता नहीं है?',
        'have_account'   => 'पहले से खाता है?',
    ],

    // Common
    'common' => [
        'save'    => 'सहेजें',
        'update'  => 'अपडेट करें',
        'cancel'  => 'रद्द करें',
        'delete'  => 'हटाएं',
        'back'    => 'वापस',
        'confirm' => 'क्या आप सुनिश्चित हैं?',
        'yes'     => 'हाँ',
        'no'      => 'नहीं',
        'actions' => 'क्रियाएं',
        'days_left'   => ':n दिन बचे',
        'overdue'     => 'समय बीत गया!',
        'no_deadline' => 'कोई समय-सीमा नहीं',
    ],

    // Language switcher
    'language' => [
        'en' => 'English',
        'hi' => 'हिन्दी',
        'switch' => 'भाषा बदलें',
    ],
];
