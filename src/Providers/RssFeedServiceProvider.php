<?php

namespace Botble\RssFeed\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\RssFeed\Facades\RssFeed;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;

class RssFeedServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        AliasLoader::getInstance()->alias('RssFeed', RssFeed::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/rss-feed')
            ->loadAndPublishConfigurations(['rss-feed'])
            ->loadRoutes()
            ->loadAndPublishViews();

        $this->app['events']->listen(RouteMatched::class, function () {
            if (is_plugin_active('blog') && Route::has('feeds.posts')) {
                RssFeed::addFeedLink(route('feeds.posts'), 'Posts feed');
            }
        });
    }
}
