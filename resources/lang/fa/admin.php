<?php
return [
    'meta-data' =>[
        'blog'          => 'پست منتخب بلاگ',
        'blog_title'    => 'عنوان پست',
        'blog_url'      => 'آدرس پست',
        'blog_image'    => 'تصویر پست',
        'special'       => 'دوره ویژه',
        'special_id'    => 'شناسه دوره ویژه',
        'special_image' => 'تصویر دوره ویژه',
    ],
    'catalog'       => [
        'products' => [
            'banner'           => 'بنر',
            'product_number'   => 'کد دوره',
            'product_template' => 'قالب دوره',
            'template'         => 'قالب دوره',
            'simple'           => 'ساده',
            'virtual'          => 'مجازی',
            'grouped'          => 'گروه بندی شده',
            'bundle'           => 'باندل',
            'booking'          => 'نوبت دهی',
            'configurable'     => 'قابل پیکربندی',
            'downloadable'     => 'اطلاعات قابل بارگیری',
        ]
    ],
    'customers'     => [
        'customers' => [
            'bulk-title'      => 'وارد کردن',
            'bulk-page-title' => 'وارد کردن فراگیران',
            'is_moodle_user'  => 'کاربر مودل',
            'bulk-btn-title'  => 'وارد کردن',
            'is_foriegn' => 'اتباع خارجی'
        ]
    ],
    'sales'         => [
        'orders'       => [
            'product_number' => 'کد دوره',
            'complete'       => 'تایید ثبت نام',
            'ims'              => [
                'column' => 'وضعیت همگام سازی',
                'synced'  => 'همگام سازی',
                'not_synced'  => 'عدم همگام سازی',
            ],
        ],
        'transactions' => [
            'transaction_id'  => 'کد یکتای پداختی',
            'SaleReferenceId' => 'کد مرجع فروش',
            'CardHolderPan'   => 'شماره کارت پرداخت کننده',
            'CardHolderInfo'  => 'اطلاعات پرداخت کننده',
        ],

    ],
    'datagrid'      => [
        'complete' => 'تایید ثبت نام',
        'sync-ims' => 'ثبت در IMS',
    ],
    'response'      => [
        'complete-error' => 'امکان تکمیل ثبت نام نمیباشد.'
    ],
    'notifications' => [
        'sync' => [
            'users' => ':success کاربر در مودل ثبت شد. ثبت :fail کاربر با خطا مواجه شد.'
        ]
    ],
    'promotions'=>[
        'cart-rules'=>[
            'show_in_list' => 'نمایش در لیست'
        ],
    ],
    'users' =>[
      'users'=>[
          'username'=>'نام کاربری'
      ]
    ],
    'layouts' => [
      'visit-moodle'=>'سامانه آموزش مجازی'
    ],
    'cms'=>[
        'categories' =>[
            'title' => 'دسته بندی ها',
            'add-title' => 'دسته بندی جدید',
            'edit-title' => 'ویرایش دسته بندی',
            'create-btn-title' => 'ذخیره دسته بندی',
            'edit-btn-title' => 'ویرایش دسته بندی',
            'category' => 'دسته بندی',
            'categories' => 'دسته بندی ها'
        ]
    ],
    'admin'         => [
        'sms'    => [
            'sms'                => 'پیامک',
            'sms_log'            => 'گزارش پیامک',
            'gateway'            => 'سامانه پیامکی',
            'from'               => 'از شماره',
            'username'           => 'نام کاربری',
            'password'           => 'رمزعبور',
            'notification_label' => 'اطلاعیه',
            'notifications'      => [
                'pattern'                                          => 'کد پترن',
                'verification'                                     => 'ارسال کد تایید برای ثبت نام',
                'registration'                                     => 'ارسال تاییدیه انجام ثبت نام',
                'customer-registration-confirmation-mail-to-admin' => 'پس از ثبت نام فراگیر ، یک پیامک تأیید به مدیر ارسال کنید',
                'customer'                                         => 'ارسال اطلاعات حساب به فراگیر',
                'new-order'                                        => 'ارسال پیامک بعد از ثبت سفارش جدید',
                'new-admin'                                        => 'ارسال پیامک به مدیر بعد از ثبت سفارش جدید',
                'completed-order'                                  => 'ارسال پیامک بعد از تکمیل ثبت نام',
                'new-invoice'                                      => 'ارسال پیامک بعد از تایید سفارش',
                'new-refund'                                       => 'ارسال پیامک بعد از برگشت مبلغ پرداختی',
                'new-shipment'                                     => 'ارسال پیامک بعد از ارسال محصول',
                'new-inventory-source'                             => 'ارسال پیامک به مدیر بعد از ایجاد inventory جدید',
                'cancel-order'                                     => 'ارسال پیامک بعد از لغو سفارش',
            ],
        ],
        'system' => [
            'sms'              => 'تاییدیه پیامکی',
            'sms-verification' => 'نیاز به تایید پیامکی بعد از ثبت نام',
            'sms-settings'     => 'تنظیمات پیامک',
        ]
    ]
];