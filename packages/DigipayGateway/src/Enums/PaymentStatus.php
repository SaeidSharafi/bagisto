<?php

declare(strict_types=1);

namespace DigipayGateway\Enums;

/**
 * Payment status codes returned by Digipay.
 */
class PaymentStatus
{
    public const SUCCESS = 0;
    public const INVALID_INPUT = 1054;
    public const NOT_FOUND = 9000;
    public const INVALID_TOKEN = 9001;
    public const EXPIRED = 9003;
    public const IN_PROGRESS = 9004;
    public const NOT_PAYABLE = 9005;
    public const PSP_ERROR = 9006;
    public const PAYMENT_FAILED = 9007;
    public const DUPLICATE_DIFFERENT_DATA = 9008;
    public const VERIFY_TIMEOUT = 9009;
    public const VERIFY_FAILED = 9010;
    public const VERIFY_INDETERMINATE = 9011;
    public const INVALID_STATE = 9012;
    public const CELL_REQUIRED = 9030;
    public const TICKET_NOT_POSSIBLE = 9031;

    /**
     * Get human-readable message for status code.
     *
     * @param int $code
     * @return string
     */
    public static function getMessage(int $code): string
    {
        $messages = [
            self::SUCCESS => 'عملیات با موفقیت انجام شد',
            self::INVALID_INPUT => 'پارامترهای ورودی نامعتبر است',
            self::NOT_FOUND => 'تراکنش یافت نشد',
            self::INVALID_TOKEN => 'توکن پرداخت نامعتبر است',
            self::EXPIRED => 'مهلت پرداخت به پایان رسیده است',
            self::IN_PROGRESS => 'تراکنش در حال پردازش است',
            self::NOT_PAYABLE => 'امکان پرداخت وجود ندارد',
            self::PSP_ERROR => 'خطا در ارتباط با درگاه بانک',
            self::PAYMENT_FAILED => 'پرداخت ناموفق بود',
            self::DUPLICATE_DIFFERENT_DATA => 'تراکنش تکراری با اطلاعات متفاوت',
            self::VERIFY_TIMEOUT => 'زمان تایید تراکنش به پایان رسیده',
            self::VERIFY_FAILED => 'تایید تراکنش ناموفق بود',
            self::VERIFY_INDETERMINATE => 'وضعیت تراکنش نامشخص است',
            self::INVALID_STATE => 'وضعیت درخواست نامعتبر است',
            self::CELL_REQUIRED => 'شماره موبایل الزامی است',
            self::TICKET_NOT_POSSIBLE => 'امکان ایجاد تیکت برای این کاربر وجود ندارد',
        ];

        return $messages[$code] ?? 'خطای نامشخص';
    }

    /**
     * Check if status indicates success.
     *
     * @param int $code
     * @return bool
     */
    public static function isSuccess(int $code): bool
    {
        return $code === self::SUCCESS;
    }
}
