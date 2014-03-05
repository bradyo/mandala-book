<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'username',
                    'password' => 'password',
                    'dbname'   => 'database',
                )
            )
        )
    ),
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host' => 'smtp.localhost',
                'port' => 587,
                'connection_class' => 'plain',
                'connection_config' => array(
                    'username' => '',
                    'password' => '',
                    'ssl' => 'tls'
                ),
            ),
        ),
    ),
    'stripe' => array(
        'secret_key' => '',
    ),
    'facebook' => array(
        'app_id' => '',
        'app_secret' => '',
    ),
);
