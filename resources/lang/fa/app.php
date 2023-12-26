<?php
return [
    'sms-timer' => 'ارسال مجدد کد تا {} دیگر',
    'customer'  => [
        'account' => [
            'profile' => [
                'national_code'   => 'کد ملی/کد اتباع',
                'father_name'     => 'نام پدر',
                'education_field' => 'رشته تحصیلی',
            ],
            'moodle'=>[
                'index'=>[
                'page-title' => 'دوره های مجازی'
                ]
            ]
        ],
        'reviews' => [
            'comment' => 'نظر'
        ]
    ],
    'checkout'  => [
        'cart' => [
            'item' => [
                'exist-add'       => 'امکان ثبت نام بیش از یکبار نمیباشد',
                'order-exist-add' => 'شما قبلا در این دوره ثبت نام کرده‌اید',
            ]
        ]
    ],
    'product'=>[
        'free'=> 'رایگان'
    ],

    'velocity'  => [
        'title'          => 'تنظیمات صفحه اول',
        'admin'          => [
            'meta-data' => [
                'advertisement-top' => 'تصویر تبلیغاتی نوار بالا'
            ]
        ],
        'auth-form'      => [
            'authenticate-submit' => 'ورود',
            'phone'               => 'شماره موبایل'
        ],
        'otp-form'       => [
            'verify'              => 'کد تایید را وارد نمایید',
            'form-verfiy-text'    => 'حساب کاربری با شماره موبایل :phone وجود ندارد.',
            'form-verfiy-desc'    => 'برای ساخت حساب جدید، کد تایید برای این شماره ارسال گردید.',
            'forgotpassword-text' => 'کد تایید برای شماره :phone ارسال گردید.',
            'confirm'             => 'تایید',
        ],
        'reset-password' => [
            'confirm-password' => 'تکرار کلمه عبور',
        ]
    ],
    'promotions'=>[
        'cart-rules' =>[
            'list'=> 'انتخاب تخفیف'
        ]
    ],
    'response' =>[
        'sync-ims-success' => 'ثبت نام با موفقیت در سامانه IMS ثبت شد',
        'sync-ims-fail' => 'ثبت اطلاعات در سامانه IMS با خطا مواجه شد',

        'complete-success' => 'ثبت نام با موفقیت تایید شد',
        'too-many-attemps' => 'تعداد درخواست بیش از حد مجاز میباشد. لطفا چند دقیقه صبر کرده و مجددا تلاش فرمایید'
    ]
];