<?php

namespace Botble\RssFeed\Helpers;

class FeedContentType
{
    public static array $typeMap = [
        'atom' => ['response' => 'application/xml', 'link' => 'application/atom+xml'],
        'json' => ['response' => 'application/json', 'link' => 'application/feed+json'],
        'rss' => ['response' => 'application/xml', 'link' => 'application/rss+xml'],
    ];

    public static array $defaults = [
        'response' => 'application/xml',
        'link' => 'application/atom+xml',
    ];

    public static function forResponse(string $feedFormat): string
    {
        $contentType = config('plugins.rss-feed.rss-feed.feeds.main.contentType');
        $mappedType = self::$typeMap[$feedFormat]['response'] ?? self::$defaults['response'];

        return empty($contentType)
            ? $mappedType
            : $contentType;
    }

    public static function forLink(string $feedFormat): string
    {
        $type = config('plugins.rss-feed.rss-feed.feeds.main.type');
        $mappedType = self::$typeMap[$feedFormat]['link'] ?? self::$defaults['link'];

        return empty($type)
            ? $mappedType
            : $type;
    }
}
