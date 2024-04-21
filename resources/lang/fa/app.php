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
                'page-title' => 'دوره های من'
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
        'sync-ims-api-key' => 'کلید API سامانه IMS وجود ندارد',
        'sync-ims-unauthorized' => 'دسترسی غیرمجاز به سامانه IMS',
        'sync-ims-porduct-number' => 'کد دوره ثبت نشده است',
        'sync-ims-customer-incomplete' => 'اطلاعات فراگیر کامل نمی باشد',
        'sync-ims-course-notfound' => 'اطلاعات دوره در سامانه IMS یافت نشد',

        'sync-rouyesh-success' => 'ثبت نام با موفقیت در سامانه رویش ثبت شد',
        'sync-rouyesh-fail' => 'ثبت اطلاعات در سامانه رویش با خطا مواجه شد',
        'sync-rouyesh-api-key' => 'کلید API سامانه رویش وجود ندارد',
        'sync-rouyesh-unauthorized' => 'دسترسی غیرمجاز به سامانه رویش',
        'sync-rouyesh-porduct-number' => 'کد کلاس رویش ثبت نشده است',
        'sync-rouyesh-customer-incomplete' => 'اطلاعات فراگیر کامل نمی باشد',
        'sync-rouyesh-course-notfound' => 'اطلاعات دوره در سامانه رویش یافت نشد',



        'complete-success' => 'ثبت نام با موفقیت تایید شد',
        'too-many-attemps' => 'تعداد درخواست بیش از حد مجاز میباشد. لطفا چند دقیقه صبر کرده و مجددا تلاش فرمایید'
    ]
];
