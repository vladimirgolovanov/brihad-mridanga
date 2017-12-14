<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => true,
    'allowedOrigins' => explode(',', env('CORS_ORIGINS')),
    'allowedHeaders' => ['Overwrite', 'Destination', 'Content-Type', 'Depth', 'User-Agent', 'Translate', 'Range', 'Content-Range', 'Timeout', 'X-File-Size', 'X-Requested-With', 'If-Modified-Since', 'X-File-Name', 'Cache-Control', 'Location', 'Lock-Token', 'If'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];

