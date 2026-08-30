<?php

namespace Tests\Unit;

use App\Support\MoneyFormatter;
use PHPUnit\Framework\TestCase;

class MoneyFormatterTest extends TestCase
{
    public function test_it_formats_vnd_and_usd_for_vietnamese_customers(): void
    {
        $this->assertSame('18.900.000 đ', MoneyFormatter::format(18900000, 'VND'));
        $this->assertSame('$399', MoneyFormatter::format(399, 'USD'));
    }

    public function test_it_can_format_by_a_future_language_locale(): void
    {
        $this->assertSame('$1,299.50', MoneyFormatter::format(1299.5, 'USD', 'en-US'));
        $this->assertSame('18,900,000 đ', MoneyFormatter::format(18900000, 'VND', 'en-US'));
    }

    public function test_vnd_is_the_default_currency(): void
    {
        $this->assertSame('250.000 đ', MoneyFormatter::format(250000));
        $this->assertSame('VND', MoneyFormatter::normalizeCurrency(null));
    }
}
