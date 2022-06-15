@component('mail::message', [
    'uri_query' => $uri_query ?? '',
    'unsubscribe_info' => $unsubscribe_info ?? false
    ])
    # {{ $title }}

    @if(!empty($message_html))
        {!! $message_html !!}
    @endif

@endcomponent
