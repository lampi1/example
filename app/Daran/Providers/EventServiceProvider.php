<?php

namespace App\Daran\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'App\Daran\Events\PageUpdated' => [
            'App\Daran\Listeners\UpdatePageSlug',
        ],
        'App\Daran\Events\CategoryUpdated' => [
            'App\Daran\Listeners\UpdateCategorySlug',
        ],
        'App\Daran\Events\EventCategoryUpdated' => [
            'App\Daran\Listeners\UpdateEventCategorySlug',
        ],
        'App\Daran\Events\EventUpdated' => [
            'App\Daran\Listeners\UpdateEventSlug',
        ],
        'App\Daran\Events\FamilyUpdated' => [
            'App\Daran\Listeners\UpdateFamilySlug',
        ],
        'App\Daran\Events\FaqUpdated' => [
            'App\Daran\Listeners\UpdateFaqSlug',
        ],
        'App\Daran\Events\GalleryUpdated' => [
            'App\Daran\Listeners\UpdateGallerySlug',
        ],
        'App\Daran\Events\ItemUpdated' => [
            'App\Daran\Listeners\UpdateItemSlug',
        ],
        'App\Daran\Events\LandingPageUpdated' => [
            'App\Daran\Listeners\UpdateLandingPageSlug',
        ],
        'App\Daran\Events\NewsCategoryUpdated' => [
            'App\Daran\Listeners\UpdateNewsCategorySlug',
        ],
        'App\Daran\Events\NewsUpdated' => [
            'App\Daran\Listeners\UpdateNewsSlug',
        ],
        'App\Daran\Events\PostCategoryUpdated' => [
            'App\Daran\Listeners\UpdatePostCategorySlug',
        ],
        'App\Daran\Events\PostUpdated' => [
            'App\Daran\Listeners\UpdatePostSlug',
        ],
        'App\Daran\Events\ServiceCategoryUpdated' => [
            'App\Daran\Listeners\UpdateServiceCategorySlug',
        ],
        'App\Daran\Events\ServiceUpdated' => [
            'App\Daran\Listeners\UpdateServiceSlug',
        ],
        'App\Daran\Events\GalleryCategoryUpdated' => [
            'App\Daran\Listeners\UpdateGalleryCategorySlug',
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
