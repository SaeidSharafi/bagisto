<?php
return [
    'simple' => [
        'key'   => 'simple',
        'name'  => 'Simple',
        'class' => 'App\Shop\Product\Types\JeduSimple',
        'sort'  => 1,
    ],

    'configurable' => [
        'key'   => 'configurable',
        'name'  => 'Configurable',
        'class' => 'App\Shop\Product\Types\JeduConfigurable',
        'sort'  => 2,
    ],

    'template' => [
        'key'   => 'template',
        'name'  => 'Template',
        'class' => \App\Shop\Product\Types\JeduTemplate::class,
        'sort'  => 3,
    ],
];