<?php
chdir(dirname(__DIR__)); // set root path to parent folder

if ($_SERVER['HTTP_HOST'] === 'mandala.local') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('show_startup_errors', '1');
}


require_once(__DIR__ . '/../bootstrap.php');

$config = require(__DIR__ . '/../config/application.config.php');
$app = Zend\Mvc\Application::init($config);
$app->run();
