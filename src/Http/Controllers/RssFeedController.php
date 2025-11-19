<?php

namespace Botble\RssFeed\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Blog\Models\Post;
use Botble\JobBoard\Models\Job;
use Botble\Media\Facades\RvMedia;
use Botble\RealEstate\Repositories\Interfaces\ProjectInterface;
use Botble\RealEstate\Repositories\Interfaces\PropertyInterface;
use Botble\RssFeed\Facades\RssFeed;
use Botble\RssFeed\FeedItem;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;

class RssFeedController extends PublicController
{
    public function show(string $name)
    {
        $feedItems = collect();

        $label = null;

        switch ($name) {
            case 'posts':
                abort_unless(is_plugin_active('blog'), 404);

                $data = Post::query()
                    ->wherePublished()->latest()
                    ->take(20)
                    ->get();

                foreach ($data as $item) {
                    if (! $item instanceof Post) {
                        continue;
                    }

                    $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());

                    $category = $item->categories()->value('name');

                    $author = (string) $item->author?->name ?: Theme::getSiteTitle();

                    $feedItem = FeedItem::create()
                        ->id($item->getKey())
                        ->title(BaseHelper::clean($item->name))
                        ->summary(BaseHelper::clean($item->description ?: $item->name))
                        ->updated($item->updated_at)
                        ->enclosure($imageURL)
                        ->enclosureType(RvMedia::getMimeType(RvMedia::getRealPath($item->image ?: RvMedia::getDefaultImage())))
                        ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                        ->when($category, fn (FeedItem $feedItem) => $feedItem->category($category))
                        ->link((string) $item->url)
                        ->when(! empty($author), function (FeedItem $feedItem) use ($item, $author) {
                            if (method_exists($feedItem, 'author')) {
                                return $feedItem->author($author);
                            }

                            return $feedItem
                                ->authorName($author)
                                ->authorEmail((string) $item->author?->email);
                        });

                    $feedItems[] = $feedItem;
                }

                $label = trans('plugins/rss-feed::rss-feed.posts');

                break;

            case 'jobs':
                abort_if(! is_plugin_active('job-board') || ! class_exists(Job::class), 404);

                $jobs = Job::query()
                    ->active()
                    ->take(20)
                    ->with('author')
                    ->get();

                foreach ($jobs as $item) {
                    $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());
                    $feedItem = FeedItem::create()
                        ->id($item->id)
                        ->title(clean($item->name))
                        ->summary(clean($item->description))
                        ->updated($item->updated_at)
                        ->enclosure($imageURL)
                        ->enclosureType(RvMedia::getMimeType(RvMedia::getRealPath($item->image ?: RvMedia::getDefaultImage())))
                        ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                        ->link((string) $item->url);

                    if (method_exists($feedItem, 'author')) {
                        $feedItem = $feedItem->author($item->author_id && $item->author->name ? $item->author->name : '');
                    } else {
                        $feedItem = $feedItem
                            ->authorName($item->author_id && $item->author->name ? $item->author->name : '')
                            ->authorEmail($item->author_id && $item->author->email ? $item->author->email : '');
                    }

                    $feedItems[] = $feedItem;
                }

                $label = trans('plugins/rss-feed::rss-feed.jobs');

                break;

            case 'properties':
                abort_if(! is_plugin_active('real-estate') || ! interface_exists(PropertyInterface::class), 404);

                $label = trans('plugins/rss-feed::rss-feed.properties');

                $data = app(PropertyInterface::class)->getProperties([], [
                    'take' => 20,
                    'with' => ['slugable', 'categories', 'author'],
                ]);

                foreach ($data as $item) {
                    $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());

                    $feedItem = FeedItem::create()
                        ->id($item->id)
                        ->title(BaseHelper::clean($item->name))
                        ->summary(BaseHelper::clean($item->description))
                        ->updated($item->updated_at)
                        ->enclosure($imageURL)
                        ->enclosureType(RvMedia::getMimeType(RvMedia::getRealPath($item->image ?: RvMedia::getDefaultImage())))
                        ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                        ->category((string) $item->category->name)
                        ->link((string) $item->url);

                    if (method_exists($feedItem, 'author')) {
                        $feedItem = $feedItem->author($item->author_id && $item->author->name ? $item->author->name : '');
                    } else {
                        $feedItem = $feedItem
                            ->authorName($item->author_id && $item->author->name ? $item->author->name : '')
                            ->authorEmail($item->author_id && $item->author->email ? $item->author->email : '');
                    }

                    $feedItems[] = $feedItem;
                }

                break;

            case 'projects':
                abort_if(! is_plugin_active('real-estate') || ! interface_exists(ProjectInterface::class), 404);

                $label = trans('plugins/rss-feed::rss-feed.projects');

                $data = app(ProjectInterface::class)->getProjects(
                    [],
                    [
                        'take' => 20,
                        'width' => ['categories'],
                    ]
                );

                foreach ($data as $item) {
                    $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());

                    $feedItem = FeedItem::create()
                        ->id($item->id)
                        ->title(BaseHelper::clean($item->name))
                        ->summary(BaseHelper::clean($item->description))
                        ->updated($item->updated_at)
                        ->enclosure($imageURL)
                        ->enclosureType(RvMedia::getMimeType(RvMedia::getRealPath($item->image ?: RvMedia::getDefaultImage())))
                        ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                        ->category((string) $item->category->name)
                        ->link((string) $item->url);

                    if (method_exists($feedItem, 'author')) {
                        $feedItem = $feedItem->author($item->author_id && $item->author->name ? $item->author->name : '');
                    } else {
                        $feedItem = $feedItem
                            ->authorName($item->author_id && $item->author->name ? $item->author->name : '')
                            ->authorEmail($item->author_id && $item->author->email ? $item->author->email : '');
                    }

                    $feedItems[] = $feedItem;
                }

                break;

            case 'products':
                abort_if(! is_plugin_active('ecommerce') || ! function_exists('get_products'), 404);

                $label = trans('plugins/rss-feed::rss-feed.products');

                $products = get_products([
                    'take' => 20,
                    'with' => ['categories', 'slugable'],
                ]);

                foreach ($products as $item) {
                    $imageURL = RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());

                    $feedItem = FeedItem::create()
                        ->id($item->id)
                        ->title(BaseHelper::clean($item->name))
                        ->summary(BaseHelper::clean($item->description))
                        ->updated($item->updated_at)
                        ->enclosure($imageURL)
                        ->enclosureType(RvMedia::getMimeType(RvMedia::getRealPath($item->image ?: RvMedia::getDefaultImage())))
                        ->enclosureLength(RssFeed::remoteFilesize($imageURL))
                        ->category((string) $item->categories->first()?->name)
                        ->link((string) $item->url)
                        ->authorName(Theme::getSiteTitle());

                    $feedItems[] = $feedItem;
                }

                break;
            default:
                $feedItems = apply_filters('rss_feed_items', $feedItems, $name);

                abort_if($feedItems->isEmpty(), 404);
        }

        abort_if(! $label || $feedItems->isEmpty(), 404);

        return RssFeed::renderFeedItems(
            $feedItems,
            trans('plugins/rss-feed::rss-feed.name_feed', ['name' => $label]),
            trans('plugins/rss-feed::rss-feed.latest_posts_from_site_title', ['site_title' => Theme::getSiteTitle()])
        );
    }
}
