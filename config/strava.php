<?php

return [
    'client_id' => env('STRAVA_CLIENT_ID'),
    'client_secret' => env('STRAVA_CLIENT_SECRET'),
    'redirect' => env('STRAVA_REDIRECT_URI'),
    'scopes' => 'read,activity:read_all',
];