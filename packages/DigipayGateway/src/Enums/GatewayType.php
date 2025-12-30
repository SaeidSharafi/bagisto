<?php

declare(strict_types=1);

namespace DigipayGateway\Enums;

/**
 * Gateway types for preferredGateway parameter.
 */
class GatewayType
{
    public const WALLET = 0;
    public const IPG = 2;
    public const CREDIT = 5;
    public const BNPL = 13;

    /**
     * Get label for gateway type.
     *
     * @param int $type
     * @return string
     */
    public static function getLabel(int $type): string
    {
        $labels = [
            self::WALLET => 'کیف پول',
            self::IPG => 'درگاه بانکی',
            self::CREDIT => 'اعتباری',
            self::BNPL => 'خرید اقساطی',
        ];

        return $labels[$type] ?? 'نامشخص';
    }

    /**
     * Get all available types.
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        return [
            self::WALLET => self::getLabel(self::WALLET),
            self::IPG => self::getLabel(self::IPG),
            self::CREDIT => self::getLabel(self::CREDIT),
            self::BNPL => self::getLabel(self::BNPL),
        ];
    }
}
