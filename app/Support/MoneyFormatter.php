<?php

namespace App\Support;

final class MoneyFormatter
{
    public const DEFAULT_CURRENCY = 'VND';

    public const SUPPORTED_CURRENCIES = [
        'VND' => 'Việt Nam đồng (đ)',
        'USD' => 'Đô la Mỹ ($)',
    ];

    public static function format(
        int|float|string $amount,
        ?string $currency = null,
        ?string $locale = null,
    ): string {
        $currency = self::normalizeCurrency($currency);
        $isVietnamese = str_starts_with(
            strtolower(str_replace('_', '-', $locale ?? 'vi')),
            'vi',
        );
        $numericAmount = (float) $amount;
        $decimalPlaces = $currency === 'VND' || floor($numericAmount) === $numericAmount
            ? 0
            : 2;
        $formatted = number_format(
            $numericAmount,
            $decimalPlaces,
            $isVietnamese ? ',' : '.',
            $isVietnamese ? '.' : ',',
        );

        return match ($currency) {
            'VND' => $formatted.' đ',
            'USD' => '$'.$formatted,
            default => $formatted.' '.$currency,
        };
    }

    public static function normalizeCurrency(?string $currency): string
    {
        $currency = strtoupper(trim((string) $currency));

        return $currency !== '' ? $currency : self::DEFAULT_CURRENCY;
    }
}
