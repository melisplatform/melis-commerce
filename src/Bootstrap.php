<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$env = getenv('MELIS_PLATFORM');
$config = require(__DIR__ . "/config/autoload/platforms/{$env}.php");

$capsule = new Capsule;

$capsule->addConnection(array_merge(
    $config['db'],
    [
        'driver' => 'mysql',
        'host' => $config['db']['hostname'],
        'collation' => 'utf8_general_ci',
    ]
));

$capsule->setAsGlobal();
$capsule->bootEloquent();


