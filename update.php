<?php
 require_once(__DIR__ . '/bootstrap.php');

if (! defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', (isset($_SERVER['APPLICATION_ENV']) ? $_SERVER['APPLICATION_ENV'] : 'development'));
}
echo 'configuring environment: ' . APPLICATION_ENV . "\n";

// copy environment config into active config file
exec('cp /vagrant/config/' . APPLICATION_ENV . '/local.php /vagrant/config/autoload/local.php');

// run database migrations
exec('php /vagrant/vendor/bin/doctrine-module migrations:migrate');
