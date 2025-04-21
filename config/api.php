<?php

return [
    'api_base' => env('POLICE_SCORECARD_API_BASE', 'https://api.policescorecard.org'),
    'api_version' => env('POLICE_SCORECARD_API_VERSION', 'v1'),
    'api_key' => env('POLICE_SCORECARD_API_KEY', null),
    'api_timeout' => env('POLICE_SCORECARD_API_TIMEOUT', 30),
    'admin_token' => env('POLICE_SCORECARD_ADMIN_TOKEN', null),
    'cache_expire' => env('POLICE_SCORECARD_CACHE_EXPIRE', 3600),
];
