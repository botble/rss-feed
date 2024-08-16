<?php

namespace Botble\RssFeed\Facades;

use Botble\RssFeed\Supports\RssFeed as RssFeedSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Botble\RssFeed\Supports\RssFeed addFeedLink(string $url, string $title)
 * @method static \Botble\RssFeed\Feed renderFeedItems(\Illuminate\Support\Collection $items, string $title, string $description)
 * @method static int remoteFilesize(string $url)
 *
 * @see \Botble\RssFeed\Supports\RssFeed
 */
class RssFeed extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RssFeedSupport::class;
    }
}
