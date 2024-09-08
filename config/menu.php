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
        ],
        [
            'key'        => 'carousel',
            'name'       => 'admin.carousel.category.title',
            'route'      => 'admin.carousel.category.index',
            'sort'       => 5,
            'icon-class' => 'carousel-icon',

        ],
        [
            'key'        => 'carousel.category',
            'name'       => 'admin.carousel.category.title',
            'route'      => 'admin.carousel.category.index',
            'sort'       => 2,
            'icon-class' => '',

        ],
        [
            'key'        => 'carousel.item',
            'name'       => 'admin.carousel.item.title',
            'route'      => 'admin.carousel.item.index',
            'sort'       => 2,
            'icon-class' => 'temp-icon',
        ],
        [
            'key'        => 'settings.center',
            'name'       => 'admin.center.title',
            'route'      => 'admin.center.index',
            'sort'       => 2,
            'icon-class' => 'temp-icon',
        ],
        [
            'key'        => 'customers.contact_request',
            'name'       => 'admin.contactus.title',
            'route'      => 'admin.contact-request.index',
            'sort'       => 2,
            'icon-class' => 'temp-icon',
        ],
    ],

    'customer' => [
        [
            'key'   => 'account.moodle',
            'name'  => 'app.customer.account.moodle.index.page-title',
            'route' => 'customer.my-course.index',
            'sort'  => 8,
            'icon'  => 'fa fa-desktop pl-2',
        ],
        [
            'key'   => 'account.ims',
            'name'  => 'app.customer.account.ims.title',
            'route' => 'customer.ims.redirect',
            'target' => '_blank',
            'sort'  => 9,
            'icon'  => 'fa fa-desktop pl-2',
        ],
    ]
];
