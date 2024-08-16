<?php

namespace Botble\RssFeed\Exceptions;

use Botble\RssFeed\FeedItem;
use Exception;

class InvalidFeedItem extends Exception
{
    public ?FeedItem $subject;

    public static function notFeedable($subject): self
    {
        return (new self('Object does not implement `Botble\RssFeed\Contracts\Feedable`'))->withSubject($subject);
    }

    public static function notAFeedItem($subject): self
    {
        return (new self('`toFeedItem` should return an instance of `Botble\RssFeed\Contracts\Feedable`'))->withSubject($subject);
    }

    public static function missingField(FeedItem $subject, $field): self
    {
        return (new self("Field `{$field}` is required"))->withSubject($subject);
    }

    protected function withSubject($subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
