<?php

declare(strict_types=1);

namespace DigipayGateway\Enums;

/**
 * Callback result values.
 */
class CallbackResult
{
    public const SUCCESS = 'SUCCESS';
    public const FAILURE = 'FAILURE';

    /**
     * Check if result indicates success.
     *
     * @param string $result
     * @return bool
     */
    public static function isSuccess(string $result): bool
    {
        return strtoupper($result) === self::SUCCESS;
    }
}
