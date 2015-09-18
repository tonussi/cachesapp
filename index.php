<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr'
));

// Register view rendering

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'views'
));

// Our web handlers

// Heroku production settings
$dbopts = parse_url(getenv('DATABASE_URL'));

// Dev settings
// $dbopts["port"] = "5432";
// $dbopts["host"] = "localhost";
// $dbopts["user"] = "lucastonussi";
// $dbopts["pass"] = "postgres";

$app->register(new Herrera\Pdo\PdoServiceProvider(), array(
    'pdo.dsn' => 'pgsql:dbname=dbcaches' . ltrim($dbopts["path"], '/') . ';host=' . $dbopts["host"],
    'pdo.port' => $dbopts["port"],
    'pdo.username' => $dbopts["user"],
    'pdo.password' => $dbopts["pass"]
));

$app->get('/', function () use($app) {

    $app['monolog']->addDebug('logging output.');

    $st = $app['pdo']->prepare('select * from caches');

    $st->execute();

    $caches = array();

    while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
        $app['monolog']->addDebug('Row ' . $row['id']);
        $caches[] = $row;
    }

    $lazyLoad = 10;

    return $app['twig']->render('index.twig', array(
        'caches' => $caches,
        'lazyLoad' => $lazyLoad
    ));
});

$app->run();
