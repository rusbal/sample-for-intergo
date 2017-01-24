<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{!! elixir('css/app.css') !!}" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.css" media="screen" title="no title" charset="utf-8">

    <!-- Scripts -->
    <script>
window.Laravel = {!! javascriptVariables() !!};
    </script>
</head>
<body>

    <div id="app">
        @include('layouts._nav')
        @yield('content')
    </div>

    <!-- Scripts -->
    @if (Route::current()->getName() === 'subscription.create')
        <!--
        Do not load js/app.js
        Stripe Credit Card form doesn't work otherwise.
        -->
        <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    @else
        <script src="{!! elixir('js/app.js') !!}"></script>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.7/semantic.min.js" charset="utf-8"></script>

    @yield('script')
</body>
</html>
