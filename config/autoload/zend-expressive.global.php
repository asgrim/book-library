<?php
declare(strict_types=1);

return [
    'debug' => false,
    'config_cache_enabled' => false,
    'zend-expressive' => [
        'error_handler' => [
            'template_404'   => 'error::404',
            'template_error' => 'error::error',
        ],
        'raise_throwables' => true,
    ],
];
