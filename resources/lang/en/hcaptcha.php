<?php

return [
    'validation' => [
        'hcaptcha' => 'We could not verify that you are human. Please try again.',
    ],

    'settings' => [
        'title' => 'hCaptcha',
        'description' => 'Configure hCaptcha settings',
        'enable' => 'Enable hCaptcha',
        'help_text' => 'Obtain your hCaptcha keys from the <a>hCaptcha dashboard</a>.',
        'site_key' => 'Site Key',
        'secret_key' => 'Secret Key',
        'enable_form' => 'Enable for Form',
    ],

    'forms' => [
        'admin_login' => 'Admin login form',
        'admin_forgot_password' => 'Admin forgot password form',
        'admin_reset_password' => 'Admin reset password form',
    ],
];
