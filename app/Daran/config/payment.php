<?php

return [
    'default_gateway' => 'unicredit',

    // The default options for every gateways.
    'default_options' => [
        'test_mode' => true,
    ],

    'gateways' => [
        'unicredit' => [
            'driver' => 'unicredit',
            'options' => [
                'username' => env('PAYPAL_USERNAME'),
                'password' => env('PAYPAL_PASSWORD'),
                'signature' => env('PAYPAL_SIGNATURE'),
                'test_mode' => env('PAYPAL_TEST_MODE'),
            ],
        ],
        // other gateways
    ],
];
