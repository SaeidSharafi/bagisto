<?php

return [
    'admin' => [
        [
            'key'        => 'cms.categories',
            'name'       => 'admin::app.cms.categories.categories',
            'route'      => 'admin.cms.category.index',
            'sort'       => 2,
            'icon-class' => '',
        ]
    ],

    'customer' => [
         [
            'key'   => 'account.moodlle',
            'name'  => 'app.customer.account.moodle.index.page-title',
            'route' =>'customer.moodle.index',
            'sort'  => 8,
        ]
    ]
];
