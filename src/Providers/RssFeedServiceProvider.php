<?php

namespace Botble\RssFeed\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\RssFeed\Facades\RssFeed;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;

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

        $this->app['events']->listen(RouteMatched::class, function (): void {
            if (is_plugin_active('blog')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'posts']),
                    __(':name feed', ['name' => __('Posts')])
                );
            }

            if (is_plugin_active('job-board')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'jobs']),
                    __(':name feed', ['name' => __('Jobs')])
                );
            }

            if (is_plugin_active('real-estate')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'properties']),
                    __(':name feed', ['name' => __('Properties')])
                );

                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'projects']),
                    __(':name feed', ['name' => __('Projects')])
                );
            }

            if (is_plugin_active('ecommerce')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'products']),
                    __(':name feed', ['name' => __('Products')])
                );
            }

            do_action('rss_feed.add_feed_link');
        });
    }
}
