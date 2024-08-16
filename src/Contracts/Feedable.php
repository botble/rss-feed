<?php

namespace Botble\RssFeed\Contracts;

use Botble\RssFeed\FeedItem;

interface Feedable
{
    public function toFeedItem(): FeedItem;
}
