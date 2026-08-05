<?php

return [

    'name' => env('THLIN_ADMIN_NAME', 'Daniel Walker'),
    'email' => env('THLIN_ADMIN_EMAIL', 'daniel.walker@thehealthline.ca'),
    'password' => env('THLIN_ADMIN_PASSWORD', 'Security123!'),
    'max_users' => (int) env('THLIN_ADMIN_MAX_USERS', 10),

];
