<?php
return array(
    'design_module' => array(
        'render_script' => '/vagrant/scripts/design-render.js',
        'files_path' => '/vagrant/data/design-files',
        'thumbnails_path' => '/vagrant/data/design-thumbnails',
    ),
    'controllers' => array(
        'invokables' => array(
            'design' => 'Mandala\DesignModule\DesignController',
            'design_favorite' => 'Mandala\DesignModule\DesignFavoriteController',
        ),
    ),
);