<?php

namespace Modules\Shop\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Modules\Admin\Entities\Attribute;
use Facades\Modules\Shop\Services\CartService;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('shop::layouts.app', function ($view) {
            $tags = DB::table('attribute_car')
                    ->where('attribute_id', Attribute::whereName('tags')->first()->id)
                    ->take(10)->pluck('value')->transform(function($tag) {
                        return array_filter(explode(';', $tag));
                    })->flatten()->unique()->values()->take(10);

            $view->with('tags', $tags)
                ->with('itemCount', app('cart')->itemCount())
                ->with('cartTotal', app('cart')->total());
        });
    }
}
