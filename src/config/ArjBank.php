<?php

return [
    'mode' => env('ARJ_MODE', 'test'), // test or live
    'test_endpoint' => 'https://securepayments.alrajhibank.com.sa/pg/payment/hosted.htm',
    'live_endpoint' => 'https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm',
    'transportal_id' => env('ARJ_TRANSPORTAL_ID'), // your transportal id
    'transportal_password' => env('ARJ_TRANSPORTAL_PASSWORD'), // your transportal password
    "resource_key" => env('ARJ_RESOURCE_KEY'), // your resource key
];
