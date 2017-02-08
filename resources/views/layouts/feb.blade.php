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

    <!-- Scripts -->
    <script>
        window.Laravel = {!! javascriptVariables() !!};
    </script>
</head>
<body class="feb {{ $viewName }}">

<div id="app">
    @include('layouts._febnav')
    @yield('content')
</div>

<script src="{!! elixir('js/app.js') !!}"></script>

@yield('script')
</body>
</html>