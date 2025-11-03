<?php

return [
    'default' => env('DEFAULT_CURRENCY', 'USD'),
    'api_key' => env('EXCHANGERATE_API_KEY'),
    'api_url' => 'https://v6.exchangerate-api.com/v6/',
    'cache_ttl' => env('CURRENCY_CACHE_TTL', 3600), // 1 hour
    'supported_currencies' => [
        'USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD', 'CHF', 'CNY', 
        'INR', 'NGN', 'GHS', 'KES', 'ZAR', 'EGP', 'MAD'
    ],
];
