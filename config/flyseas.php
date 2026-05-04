<?php

return [

    'company_name'  => 'FlySeas Travels',
    'tagline'       => 'Wings to your dream destination',
    'founded'       => 2018,
    'phone'         => env('FLYSEAS_PHONE', '+918421617391'),
    'phone_display' => env('FLYSEAS_PHONE', '+91 84216 17391'),
    'whatsapp'      => env('FLYSEAS_WHATSAPP', '918421617391'),
    'email'         => env('FLYSEAS_EMAIL', 'info@flyseastravels.com'),
    'location'      => env('FLYSEAS_LOCATION', 'Nagpur, Maharashtra'),
    'address'       => env('FLYSEAS_ADDRESS', 'Nagpur, Maharashtra, India'),
    'website'       => env('APP_URL', 'https://flyseastravels.com'),

    'social' => [
        'instagram' => env('FLYSEAS_INSTAGRAM', 'https://instagram.com/flyseastravels'),
        'facebook'  => env('FLYSEAS_FACEBOOK',  'https://facebook.com/flyseastravels'),
    ],

];
