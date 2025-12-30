<?php

return [
    // Payment method info
    'payment_title' => 'دیجی‌پی (درگاه بانکی)',
    'payment_description' => 'پرداخت امن از طریق درگاه بانکی دیجی‌پی',

    // Redirect page
    'redirecting' => 'در حال انتقال به درگاه پرداخت',
    'redirect_message' => 'لطفاً صبر کنید تا به صفحه پرداخت امن منتقل شوید...',
    'order_number' => 'شماره سفارش',
    'javascript_disabled' => 'جاوا اسکریپت غیرفعال است. لطفاً روی دکمه زیر کلیک کنید.',
    'click_to_continue' => 'ادامه پرداخت',

    // Status messages
    'payment_successful' => 'پرداخت با موفقیت انجام شد.',
    'payment_failed' => 'پرداخت ناموفق بود. لطفاً دوباره تلاش کنید.',
    'payment_cancelled' => 'پرداخت لغو شد.',
    'order_already_processed' => 'این سفارش قبلاً پردازش شده است.',
    'amount_mismatch' => 'مبلغ پرداخت با مبلغ سفارش مطابقت ندارد.',
    'unexpected_error' => 'خطای غیرمنتظره رخ داد. لطفاً دوباره تلاش کنید.',

    // Admin panel
    'admin' => [
        'title' => 'درگاه دیجی‌پی',
        'sandbox' => 'حالت آزمایشی',
        'sandbox_info' => 'فعال کردن برای تست با محیط آزمایشی دیجی‌پی',
        'client_id' => 'شناسه کلاینت',
        'client_id_info' => 'شناسه OAuth کلاینت ارائه شده توسط دیجی‌پی',
        'client_secret' => 'کلید محرمانه کلاینت',
        'username' => 'نام کاربری',
        'password' => 'رمز عبور',
        'api_version' => 'نسخه API',
        'api_version_info' => 'نسخه API دیجی‌پی برای استفاده',
        'preferred_gateway' => 'درگاه ترجیحی',
        'preferred_gateway_info' => 'روش پرداخت مورد نظر را انتخاب کنید',
        'gateway_ipg' => 'درگاه بانکی (IPG)',
        'gateway_wallet' => 'کیف پول دیجی‌پی',
    ],

    // Validation messages
    'validation' => [
        'amount_required' => 'مبلغ الزامی است',
        'provider_id_required' => 'شناسه ارائه‌دهنده الزامی است',
        'tracking_code_required' => 'کد پیگیری الزامی است',
        'result_required' => 'نتیجه پرداخت الزامی است',
    ],
];
