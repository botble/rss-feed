@php(OptimizerHelper::disable())

<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<rss
    version="2.0"
    xmlns:atom="http://www.w3.org/2005/Atom"
>
    <channel>
        <atom:link
            type="application/rss+xml"
            href="{{ url($meta['link']) }}"
            rel="self"
        />
        <title>{!! Botble\RssFeed\Helpers\Cdata::out($meta['title']) !!}</title>
        <link>{!! Botble\RssFeed\Helpers\Cdata::out(url($meta['link'])) !!}</link>
        @if (!empty($meta['image']))
            <image>
                <url>{{ $meta['image'] }}</url>
                <title>{!! Botble\RssFeed\Helpers\Cdata::out($meta['title']) !!}</title>
                <link>{!! Botble\RssFeed\Helpers\Cdata::out(url($meta['link'])) !!}</link>
            </image>
        @endif
        <description>{!! Botble\RssFeed\Helpers\Cdata::out($meta['description']) !!}</description>
        <language>{{ $meta['language'] }}</language>
        <pubDate>{{ $meta['updated'] }}</pubDate>

        @foreach ($items as $item)
            <item>
                <title>{!! Botble\RssFeed\Helpers\Cdata::out($item->title) !!}</title>
                <link>{{ url($item->link) }}</link>
                <description>{!! Botble\RssFeed\Helpers\Cdata::out($item->summary) !!}</description>
                @if (property_exists($item, 'author'))
                    <dc:creator>{!! Botble\RssFeed\Helpers\Cdata::out($item->author) !!}</dc:creator>
                @else
                    <author>{!! Botble\RssFeed\Helpers\Cdata::out(
                        $item->authorName . (empty($item->authorEmail) ? '' : ' <' . $item->authorEmail . '>'),
                    ) !!}</author>
                @endif
                <guid>{{ $item->link }}</guid>
                <pubDate>{{ $item->updated->toRssString() }}</pubDate>
                <enclosure
                    type="{{ $item->enclosureType }}"
                    url="{{ str_replace('https', 'http', $item->enclosure) }}"
                    length="{{ $item->enclosureLength }}"
                />
                @foreach ($item->category as $category)
                    <category>{{ $category }}</category>
                @endforeach
            </item>
        @endforeach
    </channel>
</rss>
