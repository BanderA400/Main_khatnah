<?php

return [
    'control_recovery' => [
        'enabled' => (bool) env('CONTROL_RECOVERY_ENABLED', false),
        'email' => env('CONTROL_RECOVERY_EMAIL'),
        'token' => env('CONTROL_RECOVERY_TOKEN'),
        'name' => env('CONTROL_RECOVERY_NAME', 'System Admin'),
        'create_if_missing' => (bool) env('CONTROL_RECOVERY_CREATE_IF_MISSING', true),
    ],
];
