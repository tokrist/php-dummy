<?php

use app\core\Application;
use app\controllers\AuthController;
use app\controllers\SiteController;

require_once __DIR__ . './../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

if(strlen($_ENV['DATA_HASH_KEY']) != 16) {
    echo '<span style="font-size:30px;">ERROR! .ENV DATA_HASH_KEY NEEDS TO BE 16 CHARACTERS LONG!<br><br><strong>LOAD TERMINATED</strong></span>';
    exit;
}

$config = [
    'hashKey' => $_ENV['DATA_HASH_KEY'],
    'userClass' => \app\models\User::class,
    'database' => [
        'host' => $_ENV['DATABASE_HOST'],
        'username' => $_ENV['DATABASE_USERNAME'],
        'password' => $_ENV['DATABASE_PASSWORD']
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->router->get('/', [SiteController::class, 'home']);

// Authentication Routes
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/profile', [AuthController::class, 'profile']);

$app->run();