<?php
$baseDir = dirname(dirname(__FILE__));

return [
    'plugins' => [
        'Crud' => $baseDir . '/vendor/friendsofcake/crud/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
    ],
];
