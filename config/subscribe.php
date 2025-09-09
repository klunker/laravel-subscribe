<?php
/*
|--------------------------------------------------------------------------
| Subscribe Configuration
|--------------------------------------------------------------------------
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Name of the subscribers table
    |--------------------------------------------------------------------------
    |
    | Here you can specify the name of the table that the package uses.
    | By default 'subscribers'.
    |
    */
    'table_name' => 'subscribers',

    /*
     |--------------------------------------------------------------------------
     | Allowed Subscribe Broadcast Channels
     |--------------------------------------------------------------------------
     |
     | This array defines all valid subscribe broadcast channels. The array key is the
     | unique identifier used in the database, and the value is a human-readable
     | description.
     |
    */
    'allowed_channels' => [
        'service' => 'Service notifications and account updates.',
        'marketing' => 'Promotional materials and special offers.',
        'promotions' => 'Weekly digest of new articles.',
    ],
];