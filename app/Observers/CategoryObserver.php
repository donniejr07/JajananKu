<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\ReportService;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function saved(Category $category): void
    {
        $this->invalidate($category);
    }

    public function deleted(Category $category): void
    {
        $this->invalidate($category);
    }

    protected function invalidate(Category $category): void
    {
        Cache::tags([ReportService::CATEGORY_TAG, "user:{$category->user_id}"])->flush();
    }
}
