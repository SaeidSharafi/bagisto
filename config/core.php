<?php

return [
    [
        'key'    => 'customer.settings.sms',
        'name'   => 'admin.admin.system.sms',
        'sort'   => 2,
        'fields' => [
            [
                'name'  => 'verification',
                'title' => 'admin.admin.system.sms-verification',
                'type'  => 'boolean',
            ],
        ],
    ],
    [
        'key'  => 'sms',
        'name' => 'admin.admin.sms.sms',
        'sort' => 7,
    ], [
        'key'  => 'sms.configure',
        'name' => 'admin.admin.system.sms-settings',
        'sort' => 1,
    ], [
        'key'    => 'sms.configure.sms_settings',
        'name'   => 'admin.admin.system.sms-settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'gateway',
                'title'         => 'admin.admin.sms.gateway',
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
                'title'         => 'admin.admin.sms.from',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'default_value' => '',
            ], [
                'name'          => 'username',
                'title'         => 'admin.admin.sms.username',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'default_value' => '',
            ], [
                'name'          => 'password',
                'title'         => 'admin.admin.sms.password',
                'type'          => 'password',
                'validation'    => 'required',
                'channel_based' => false,
                'default_value' => '',
            ],
        ],
    ], [
        'key'  => 'sms.general',
        'name' => 'admin.admin.sms.notification_label',
        'sort' => 2,
    ], [
        'key'    => 'sms.general.notifications',
        'name'   => 'admin.admin.sms.notification_label',
        'sort'   => 1,
        'fields' => [
            [
                'name'  => 'verification.status',
                'title' => 'admin.admin.sms.notifications.verification',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'verification.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'registration.status',
                'title' => 'admin.admin.sms.notifications.registration',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'registration.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'customer-registration-confirmation-mail-to-admin.status',
                'title' => 'admin.admin.sms.notifications.customer-registration-confirmation-mail-to-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'customer-registration-confirmation-mail-to-admin.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'customer.status',
                'title' => 'admin.admin.sms.notifications.customer',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'customer.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-order.status',
                'title' => 'admin.admin.sms.notifications.new-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-order.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'completed-order.status',
                'title' => 'admin.admin.sms.notifications.completed-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'completed-order.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-admin.status',
                'title' => 'admin.admin.sms.notifications.new-admin',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-admin.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-invoice.status',
                'title' => 'admin.admin.sms.notifications.new-invoice',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-invoice.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-refund.status',
                'title' => 'admin.admin.sms.notifications.new-refund',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-refund.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-shipment.status',
                'title' => 'admin.admin.sms.notifications.new-shipment',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-shipment.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'new-inventory-source.status',
                'title' => 'admin.admin.sms.notifications.new-inventory-source',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'new-inventory-source.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'cancel-order.status',
                'title' => 'admin.admin.sms.notifications.cancel-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'cancel-order.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],
            [
                'name'  => 'cancel-payment-order.status',
                'title' => 'admin.admin.sms.notifications.cancel-payment-order',
                'type'  => 'boolean',
            ],
            [
                'name'  => 'cancel-payment-order.pattern',
                'title' => 'admin.admin.sms.notifications.pattern',
                'type'  => 'text',
            ],

        ],
    ],
];
