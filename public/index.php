<?php
chdir(dirname(__DIR__)); // set root path to parent folder

require_once(__DIR__ . '/../bootstrap.php');

$config = require(__DIR__ . '/../config/application.config.php');
$app = Zend\Mvc\Application::init($config);
$app->run();
