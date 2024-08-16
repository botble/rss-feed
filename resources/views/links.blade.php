@foreach ($feeds as $name => $feed)
    <link
        type="{{ Botble\RssFeed\Helpers\FeedContentType::forLink($feed['format'] ?? 'atom') }}"
        href="{{ route("feeds.{$name}") }}"
        title="{{ $feed['title'] }}"
        rel="alternate"
    >
@endforeach
