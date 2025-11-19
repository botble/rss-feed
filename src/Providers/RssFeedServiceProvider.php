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
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews();

        $this->app['events']->listen(RouteMatched::class, function (): void {
            if (is_plugin_active('blog')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'posts']),
                    trans('plugins/rss-feed::rss-feed.name_feed', ['name' => trans('plugins/rss-feed::rss-feed.posts')])
                );
            }

            if (is_plugin_active('job-board')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'jobs']),
                    trans('plugins/rss-feed::rss-feed.name_feed', ['name' => trans('plugins/rss-feed::rss-feed.jobs')])
                );
            }

            if (is_plugin_active('real-estate')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'properties']),
                    trans('plugins/rss-feed::rss-feed.name_feed', ['name' => trans('plugins/rss-feed::rss-feed.properties')])
                );

                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'projects']),
                    trans('plugins/rss-feed::rss-feed.name_feed', ['name' => trans('plugins/rss-feed::rss-feed.projects')])
                );
            }

            if (is_plugin_active('ecommerce')) {
                RssFeed::addFeedLink(
                    route('feeds.show', ['name' => 'products']),
                    trans('plugins/rss-feed::rss-feed.name_feed', ['name' => trans('plugins/rss-feed::rss-feed.products')])
                );
            }

            do_action('rss_feed.add_feed_link');
        });
    }
}
