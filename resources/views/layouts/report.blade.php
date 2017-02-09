<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ strip_tags($reportTitle) }}</title>

    <script>window.Laravel = [];</script>
    <link href="{!! elixir('css/app.css') !!}" rel="stylesheet">
</head>

<?php

$style = [
    /* Layout ------------------------------ */

    'email-wrapped' => 'margin: 0; padding: 0; background-color: #feffff; box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.1);',
    'wrapped' => 'width: 100%; max-width: 900px;',

    /* Masthead ----------------------- */

    'email-masthead' => 'padding: 25px 0; text-align: center;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',
    'email-masthead_logo' => 'width:188px; height: 67px',
    'white_link' => 'text-decoration: none; color: white;'
];
?>

<body class="feb report">

{{--@include('layouts._nav')--}}
@include('layouts._febnav')

<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="height: 50px;">
            <table style="{{ $style['wrapped'] }} color: white;" width="500px" cellpadding="0" cellspacing="0">
                @yield('top')
            </table>
        </td>
    </tr>
    <tr>

        <!-- CONTENT -->

        <td class="content" align="center">
            <table style="{{ $style['wrapped'] }} {{ $style['email-wrapped'] }}" width="500px" cellpadding="0" cellspacing="0">
                <!-- Logo -->
                <tr>
                    <td style="{{ $style['email-masthead'] }}">
                        <a href="{{ url('/home') }}">
                            {{ Html::image('img/skubright-logo.png', null, ['style' => $style['email-masthead_logo']]) }}
                        </a>
                    </td>
                </tr>

                <!-- Email Body -->
                @yield('content')

            </table>
        </td>
    </tr>

    <!-- Footer -->
    <tr>
        <td align="center">
            <table style="{{ $style['wrapped'] }} color: white;" width="500px" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding: 20px; text-align: center;">
                        Questions? Email <a style="{{ $style['white_link'] }}" href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div id="app" ref="no-vue-err-ok"></div>
<script src="{!! elixir('js/app.js') !!}"></script>

<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

@yield('bottom')

</body>
</html>
