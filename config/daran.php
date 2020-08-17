<?php

return [
    'images' => [
        'breakpoints' => [
            'standard' => 2400,
            'mobile' => 600,
        ],
        'mobile_ratio' => '1:1',
        'iphone_icon' => 180,
        'ipad_icon' => 167,
    ],
    'ecommerce' => [
        'enable' => true,
    ],
    'analytics' => [
        'view_id' => env('ANALYTICS_VIEW_ID',null),
    ]
];
