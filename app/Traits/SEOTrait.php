<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SEOTrait
{
    public function getSeoTitle()
    {
        return $this->meta_title ?? $this->title;
    }

    public function getSeoDescription()
    {
        return $this->meta_description ?? Str::limit(strip_tags($this->content), 160);
    }

    public function getSeoKeywords()
    {
        return $this->meta_keywords ?? implode(', ', $this->tags->pluck('name')->toArray());
    }
}