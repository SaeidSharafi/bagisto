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

    // Delivery messages
    'delivery_confirmed' => 'تحویل خرید با موفقیت به دیجی‌پی اعلام شد.',
    'delivery_failed' => 'اعلام تحویل خرید به دیجی‌پی ناموفق بود.',
    'delivery_not_supported' => 'اعلام تحویل برای این نوع پرداخت پشتیبانی نمی‌شود.',
    'invalid_order_status_for_delivery' => 'وضعیت سفارش برای اعلام تحویل معتبر نیست.',

    // Refund messages
    'refund_successful' => 'بازگشت وجه با موفقیت انجام شد.',
    'refund_failed' => 'بازگشت وجه ناموفق بود.',
    'refund_inquiry_failed' => 'بررسی وضعیت بازگشت وجه ناموفق بود.',
    'already_refunded' => 'این سفارش قبلاً بازگشت وجه شده است.',
    'refund_amount_exceeds_total' => 'مبلغ بازگشت وجه از مبلغ کل سفارش بیشتر است.',
    'invalid_order_status_for_refund' => 'وضعیت سفارش برای بازگشت وجه معتبر نیست.',

    // General messages
    'order_not_found' => 'سفارش یافت نشد.',
    'not_digipay_order' => 'این سفارش از طریق دیجی‌پی پرداخت نشده است.',
    'missing_digipay_data' => 'اطلاعات پرداخت دیجی‌پی برای این سفارش یافت نشد.',
    'missing_refund_provider_id' => 'شناسه بازگشت وجه الزامی است.',

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
