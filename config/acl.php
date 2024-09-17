<?php

return [
    [
        'key'   => 'sales.orders.complete',
        'name'  => 'admin.datagrid.complete',
        'route' => 'admin.sales.orders.complete',
        'sort'  => 1,
    ],
    [
        'key' => 'customers.customers.reset-password',
        'name' => 'shop::app.customer.reset-password.title',
        'route' => 'admin.customers.reset-password',
        'sort' => 1,
    ],
    [
        'key' => 'customers.customers.impersonate',
        'name' => 'admin.customers.impersonate',
        'route' => 'admin.customers.impersonate',
        'sort' => 1,
    ],
    [
        'key'   => 'customers.contact_request',
        'name'  => 'admin.contactus.title',
        'route' => 'admin.contact-request.index',
        'sort'  => 1,
    ],
    [
        'key'   => 'customers.contact_request.view',
        'name'  => 'admin::app.acl.view',
        'route' => 'admin.contact-request.index',
        'sort'  => 1,
    ],
    [
        'key'   => 'customers.contact_request.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.contact-request.edit',
        'sort'  => 2,
    ],
    [
        'key'   => 'customers.contact_request.mass-delete',
        'name'  => 'admin::app.acl.mass-delete',
        'route' => 'admin.contact-request.mass-delete',
        'sort'  => 5,
    ],
];
