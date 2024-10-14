<?php

return [
    'paths' => ['api/*', 'dashboard-data', 'usuarios', 'cronograma', 'user-permissions', 'members', 'times'], // Inclua todas as rotas necessárias
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];

