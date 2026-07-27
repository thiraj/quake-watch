<?php

return [
    'providers' => [
        'usgs' => [
            'base_url' => env(
                'USGS_EARTHQUAKE_BASE_URL',
                'https://earthquake.usgs.gov'
            ),
            'cache_ttl' => 5,
        ],
    ],
];
