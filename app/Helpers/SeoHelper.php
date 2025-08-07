<?php

namespace App\Helpers;

use App\Models\SeoSetting;

class SeoHelper
{
    public static function getSettings()
    {
        // Try to get active SEO settings
        $seoSetting = SeoSetting::where('status', true)->first();

        // Default values if no settings exist
        return [
            'title' => $seoSetting->title ?? config('app.name', 'Your Website'),
            'meta_title' => $seoSetting->meta_title ?? config('app.name', 'Your Website'),
            'meta_description' => $seoSetting->meta_description ?? 'Welcome to our website',
            'meta_keywords' => $seoSetting->meta_keywords ?? '',
            'og_image' => $seoSetting->og_image ?? null,
            'no_index' => $seoSetting->no_index ?? false,
            'no_follow' => $seoSetting->no_follow ?? false,
        ];
    }
}