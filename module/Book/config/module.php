<?php
return array(
    'design_render_script' => '/vagrant/scripts/design-render.js',
    'design_files_path' => '/vagrant/data/design-files',
    'design_thumbnails_path' => '/vagrant/data/design-thumbnails',
    'book_files_path' => '/vagrant/data/book-files',
    'controllers' => array(
        'invokables' => array(
            'design' => 'Mandala\DesignModule\DesignController',
            'design_favorite' => 'Mandala\DesignModule\DesignFavoriteController',
            'book' => 'Mandala\DesignModule\BookController',
            'book_design' => 'Mandala\DesignModule\BookDesignController',
            'book_favorite' => 'Mandala\DesignModule\BookFavoriteController',
        ),
    ),
);