<?php

return [
    [
        'key'    => 'customer.settings.sms',
        'name'   => 'admin::app.admin.system.sms',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin::app.admin.system.sms-verification',
                'type'  => 'boolean',
            ],
        ],
    ],
    [
        'key'  => 'sms',
        'name' => 'admin::app.admin.sms.sms',
        'sort' => 7,
    ], [
        'key'  => 'sms.configure',
        'name' => 'admin::app.admin.system.sms-settings',
        'sort' => 1,
    ], [
        'key'    => 'sms.configure.sms_settings',
        'name'   => 'admin::app.admin.system.sms-settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'gateway',
                'title'         => 'admin::app.admin.sms.gateway',
                'type'          => 'select',
                'options'       => [
                    [
                        'title' => 'melipayamak',
                        'value' => 'melipayamak',
                    ], [
                        'title' => 'rangine',
                        'value' => 'rangine',
                    ],
                ],
                'channel_based' => false,
            ],
            [
                'name'          => 'from',
                'title'         => 'admin::app.admin.sms.from',
                'type'          => 'text',
                'info'          => 'admin::app.admin.sms.from-tip',
                'validation'    => 'required',
                'channel_based' => false,
                'default_value' => '',
            ], [
                'name'          => 'username',
                'title'         => 'admin::app.admin.sms.username',
                'type'          => 'text',
                'info'          => 'admin::app.admin.sms.username-tip',
                'validation'    => 'required',
                'channel_based' => false,
                'default_value' => '',
            ], [
                'name'          => 'password',
                'title'         => 'admin::app.admin.sms.password',
                'type'          => 'password',
                'info'          => 'admin::app.admin.sms.password-tip',
                'validation'    => 'required',
                'channel_based' => false,
                'default_value' => '',
            ],
        ],
    ], [
        'key'  => 'sms.general',
        'name' => 'admin::app.admin.sms.notification_label',
        'sort' => 2,
    ], [
        'key'    => 'sms.general.notifications',
        'name'   => 'admin::app.admin.sms.notification_label',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'verification.status',
                'title' => 'admin::app.admin.sms.notifications.verification',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'verification.pattern',
                'title' => 'admin::app.admin.sms.notifications.verification.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'registration.status',
                'title' => 'admin::app.admin.sms.notifications.registration',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'registration.pattern',
                'title' => 'admin::app.admin.sms.notifications.registration.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'customer-registration-confirmation-mail-to-admin.status',
                'title' => 'admin::app.admin.sms.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'customer-registration-confirmation-mail-to-admin.pattern',
                'title' => 'admin::app.admin.sms.notifications.customer-registration-confirmation-mail-to-admin.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'customer.status',
                'title' => 'admin::app.admin.sms.notifications.customer',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'customer.pattern',
                'title' => 'admin::app.admin.sms.notifications.customer.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-order.status',
                'title' => 'admin::app.admin.sms.notifications.new-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-order.pattern',
                'title' => 'admin::app.admin.sms.notifications.new-order.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-admin.status',
                'title' => 'admin::app.admin.sms.notifications.new-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-admin.pattern',
                'title' => 'admin::app.admin.sms.notifications.new-admin.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-invoice.status',
                'title' => 'admin::app.admin.sms.notifications.new-invoice',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-invoice.pattern',
                'title' => 'admin::app.admin.sms.notifications.new-invoice.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-refund.status',
                'title' => 'admin::app.admin.sms.notifications.new-refund',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-refund.pattern',
                'title' => 'admin::app.admin.sms.notifications.new-refund.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-shipment.status',
                'title' => 'admin::app.admin.sms.notifications.new-shipment',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-shipment.pattern',
                'title' => 'admin::app.admin.sms.notifications.new-shipment.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-inventory-source.status',
                'title' => 'admin::app.admin.sms.notifications.new-inventory-source',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-inventory-source.pattern',
                'title' => 'admin::app.admin.sms.notifications.new-inventory-source.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'cancel-order.status',
                'title' => 'admin::app.admin.sms.notifications.cancel-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'cancel-order.pattern',
                'title' => 'admin::app.admin.sms.notifications.cancel-order.pattern',
                'type'  => 'text',
            ],
        ],
    ],
];
