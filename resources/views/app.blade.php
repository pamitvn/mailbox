<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    {{--    <title>{{ config('app.name') }}</title>--}}

    <link href="{{ asset('/css/styles.css') }}" rel="stylesheet" />
    <link href="{{ mix('/app/css/app.css') }}" rel="stylesheet" />

    <script data-search-pseudo-elements defer
            src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"
            crossorigin="anonymous"></script>
    @if(app()->environment('production'))
        <script src="{{ mix('/app/js/manifest.js') }}" defer></script>
        <script src="{{ mix('/app/js/vendor.js') }}" defer></script>
    @else
        @routes
    @endif
    <script src="{{ mix('/app/js/app.js') }}" defer></script>
    @inertiaHead
</head>
<body>
@inertia

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
