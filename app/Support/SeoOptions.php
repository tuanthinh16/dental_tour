<?php

namespace App\Support;

class SeoOptions
{
    public const DEFAULTS = [
        'seo_site_title' => 'Dental Tour | Hành trình Việt Nam theo nhịp riêng',
        'seo_site_description' => 'Khám phá tour và trải nghiệm Việt Nam được thiết kế theo nhịp riêng cùng Dental Tour.',
        'seo_keywords' => 'tour Việt Nam, tour Phú Quốc, du lịch Việt Nam, trải nghiệm Việt Nam, tour riêng',
        'seo_og_image' => '',
        'seo_sitemap_urls' => '',
    ];

    public static function normalize(array $settings): array
    {
        return array_merge(self::DEFAULTS, array_intersect_key($settings, self::DEFAULTS));
    }

    public static function sitemapPaths(array $settings): array
    {
        return collect(preg_split('/\R/', self::normalize($settings)['seo_sitemap_urls']) ?: [])
            ->map(fn (string $path) => trim($path))
            ->filter(fn (string $path) => str_starts_with($path, '/'))
            ->unique()
            ->values()
            ->all();
    }
}
