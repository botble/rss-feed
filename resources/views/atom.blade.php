<?= '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<feed
    xmlns="http://www.w3.org/2005/Atom"
    xml:lang="{{ $meta['language'] }}"
>
    @foreach ($meta as $key => $metaItem)
        @if ($key === 'link')
            <{{ $key }}
                href="{{ url($metaItem) }}"
                rel="self"
            >
                </{{ $key }}>
            @elseif($key === 'title')
                <{{ $key }}>{!! Botble\RssFeed\Helpers\Cdata::out($metaItem) !!}</{{ $key }}>
                @elseif($key === 'description')
                    <subtitle>{{ $metaItem }}</subtitle>
                @elseif($key === 'language')

                @elseif($key === 'image')
                    @if (!empty($metaItem))
                        <logo>{!! $metaItem !!}</logo>
                    @else
                    @endif
                @else
                    <{{ $key }}>{{ $metaItem }}</{{ $key }}>
        @endif
    @endforeach
    @foreach ($items as $item)
        <entry>
            <title>{!! Botble\RssFeed\Helpers\Cdata::out($item->title) !!}</title>
            <link
                href="{{ url($item->link) }}"
                rel="alternate"
            />
            <id>{{ url($item->id) }}</id>
            <author>
                <name>{!! Botble\RssFeed\Helpers\Cdata::out($item->authorName) !!}</name>
                @if (!empty($item->authorEmail))
                    <email>{!! Botble\RssFeed\Helpers\Cdata::out($item->authorEmail) !!}</email>
                @endif
            </author>
            <summary type="html">
                {!! Botble\RssFeed\Helpers\Cdata::out($item->summary) !!}
            </summary>
            @if ($item->__isset('enclosure'))
                <link
                    type="{{ $item->enclosureType }}"
                    href="{{ url($item->enclosure) }}"
                    length="{{ $item->enclosureLength }}"
                />
            @endif
            @foreach ($item->category as $category)
                <category term="{{ $category }}" />
            @endforeach
            <updated>{{ $item->timestamp() }}</updated>
        </entry>
    @endforeach
</feed>
