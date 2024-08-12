<?php
require 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Queue\Capsule\Manager as Queue;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Dotenv\Dotenv;

// تحميل ملف .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
// إعداد قاعدة البيانات
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => env('DB_CONNECTION'),
    'host'      => env('DB_HOST'),
    'database'  => env('DB_DATABASE'),
    'port'      => env('DB_PORT'),
    'username'  => env('DB_USERNAME'),
    'password'  => env('DB_PASSWORD'),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// إعداد الحاوية (Container)
$container = new Container;
$container->singleton('db', function () use ($capsule) {
    return $capsule->getDatabaseManager();
});
$container->singleton(Dispatcher::class, function ($app) {
    return new Dispatcher($app);
});

// إعداد الـ Queue
$queue = new Queue($container);
$queue->addConnection([
    'driver'    => env('QUEUE_CONNECTION'),
    'table'     => 'jobs',
    'queue'     => 'default',
    'retry_after' => 90,
]);

$queue->setAsGlobal();