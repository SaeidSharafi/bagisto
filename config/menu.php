<?php

return [
    'admin' => [
        [
            'key'        => 'cms.categories',
            'name'       => 'admin.cms.categories.categories',
            'route'      => 'admin.cms.category.index',
            'sort'       => 2,
            'icon-class' => '',

        ],
        [
            'key'        => 'settings.sms_log',
            'name'       => 'admin.admin.sms.sms_log',
            'route'      => 'admin.sms.index',
            'sort'       => 8,
            'icon-class' => '',
        ]
    ],

    'customer' => [
         [
            'key'   => 'account.moodle',
            'name'  => 'app.customer.account.moodle.index.page-title',
            'route' =>'customer.my-course.index',
            'sort'  => 8,
            'icon' => 'fa fa-desktop pl-2',
        ]
    ]
];
