<?php

namespace App\Support;

class ThemeOptions
{
    public const DEFAULTS = [
        'ui_color_primary' => '#123D34',
        'ui_color_accent' => '#FF6C4F',
        'ui_color_background' => '#FAF8F2',
        'ui_color_text' => '#0B1F1B',
        'ui_color_surface' => '#F3EEE3',
        'ui_font_header' => 'Be Vietnam Pro',
        'ui_font_title' => 'Lora',
        'ui_font_body' => 'Inter',
    ];

    public const FONTS = [
        'Satoshi' => "'Satoshi', ui-sans-serif, system-ui, sans-serif",
        'Outfit' => "'Outfit', ui-sans-serif, system-ui, sans-serif",
        'Geist' => "'Geist', ui-sans-serif, system-ui, sans-serif",
        'Manrope' => "'Manrope', ui-sans-serif, system-ui, sans-serif",
        'DM Sans' => "'DM Sans', ui-sans-serif, system-ui, sans-serif",
        'Playfair Display' => "'Playfair Display', ui-serif, Georgia, serif",

        // Thêm 2026-08-30: bộ font mặc định mới cho điều hướng, tiêu đề và nội dung website.
        'Be Vietnam Pro' => "'Be Vietnam Pro', ui-sans-serif, system-ui, sans-serif",
        'Lora' => "'Lora', ui-serif, Georgia, serif",
        'Inter' => "'Inter', ui-sans-serif, system-ui, sans-serif",
    ];

    public static function normalize(array $settings): array
    {
        $theme = array_merge(self::DEFAULTS, array_intersect_key($settings, self::DEFAULTS));

        foreach (array_keys(self::DEFAULTS) as $key) {
            if (str_starts_with($key, 'ui_color_') && ! preg_match('/^#[0-9A-Fa-f]{6}$/', (string) $theme[$key])) {
                $theme[$key] = self::DEFAULTS[$key];
            }
        }

        foreach (['ui_font_header', 'ui_font_title', 'ui_font_body'] as $key) {
            if (! array_key_exists($theme[$key], self::FONTS)) {
                $theme[$key] = self::DEFAULTS[$key];
            }
        }

        $theme['ui_font_header_stack'] = self::FONTS[$theme['ui_font_header']];
        $theme['ui_font_title_stack'] = self::FONTS[$theme['ui_font_title']];
        $theme['ui_font_body_stack'] = self::FONTS[$theme['ui_font_body']];
        $theme['ui_color_primary_contrast'] = self::contrastText($theme['ui_color_primary']);
        $theme['ui_color_accent_contrast'] = self::contrastText($theme['ui_color_accent']);
        $theme['ui_color_text_contrast'] = self::contrastText($theme['ui_color_text']);

        return $theme;
    }

    public static function fontNames(): array
    {
        return array_keys(self::FONTS);
    }

    private static function contrastText(string $hex): string
    {
        $channels = array_map(function (string $channel): float {
            $value = hexdec($channel) / 255;

            return $value <= 0.04045
                ? $value / 12.92
                : (($value + 0.055) / 1.055) ** 2.4;
        }, [substr($hex, 1, 2), substr($hex, 3, 2), substr($hex, 5, 2)]);

        $luminance = ($channels[0] * 0.2126) + ($channels[1] * 0.7152) + ($channels[2] * 0.0722);
        $whiteContrast = 1.05 / ($luminance + 0.05);
        $darkContrast = ($luminance + 0.05) / 0.05;

        return $darkContrast >= $whiteContrast ? '#0B1F1B' : '#FFFFFF';
    }
}
