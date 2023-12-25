<?php

return [
    [
        'key' => 'sales.payment_methods.mellat',
        'name' => 'Mellat',
        'sort' => 0,
        'fields' => [
            [
                'name' => 'title',
                'title' => 'admin::app.admin.system.title',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false,
            ], [
                'name' => 'description',
                'title' => 'admin::app.admin.system.description',
                'type' => 'textarea',
                'channel_based' => false,
                'locale_based' => false,
            ], [
                'name' => 'active',
                'title' => 'admin::app.admin.system.status',
                'type' => 'boolean',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false,
            ], [
                'name' => 'sandbox',
                'title' => 'admin::app.admin.system.sandbox',
                'type' => 'boolean',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => true,
            ],
            [
                'name' => 'username',
                'title' => 'mellatgateway::app.admin.system.username',
                'info' => 'mellatgateway::app.admin.system.username-info',
                'type' => 'depends',
                'depend' => 'active:1',
                'validation' => 'required_if:active,1',
            ],
            [
                'name' => 'password',
                'title' => 'mellatgateway::app.admin.system.password',
                'info' => 'mellatgateway::app.admin.system.password-info',
                'type' => 'depends',
                'depend' => 'active:1',
                'validation' => 'required_if:active,1',
            ],
            [
                'name' => 'terminal_id',
                'title' => 'mellatgateway::app.admin.system.terminalid',
                'info' => 'mellatgateway::app.admin.system.terminalid-info',
                'type' => 'depends',
                'depend' => 'active:1',
                'validation' => 'required_if:active,1',
            ],
            [
                'name' => 'payer_id',
                'title' => 'mellatgateway::app.admin.system.payerid',
                'info' => 'mellatgateway::app.admin.system.payerid-info',
                'type' => 'depends',
                'depend' => 'active:1',
                'validation' => 'required_if:active,1',
            ],
            [
                'name' => 'sort',
                'title' => 'admin::app.admin.system.sort_order',
                'type' => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ], [
                        'title' => '2',
                        'value' => 2,
                    ], [
                        'title' => '3',
                        'value' => 3,
                    ], [
                        'title' => '4',
                        'value' => 4,
                    ],
                ],
            ]
        ]
    ]
];