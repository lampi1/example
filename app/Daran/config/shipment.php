<?php

return [
    'default_gateway' => 'mbe',

    // The default options for every gateways.
    'default_options' => [
        'test_mode' => true,
    ],

    'gateways' => [
        'mbe' => [
            'driver' => 'mbe',
            'options' => [
                'username' => env('MBE_USERNAME'),
                'password' => env('MBE_PASSWORD'),
            ],
        ],
        // other gateways
    ],
];
