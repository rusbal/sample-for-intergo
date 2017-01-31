<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $reportTitle }}</title>

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<?php

$style = [
    /* Layout ------------------------------ */

    'body' => 'margin: 0; padding: 0; width: 100%; background-color: #87d9bf; color: #7d7e7f;',
    'email-wrapper' => 'width: 100%; margin: 0; padding: 0; background-color: #87d9bf;',
    'email-wrapped' => 'margin: 0; padding: 0; background-color: #feffff; box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.1);',
    'wrapped' => 'width: 100%; max-width: 900px;',

    /* Masthead ----------------------- */

    'email-masthead' => 'padding: 25px 0; text-align: center;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',
    'email-masthead_logo' => 'width:188px; height: 67px',

//    'email-body' => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
//    'email-body_inner' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0;',
//    'email-body_cell' => 'padding: 35px;',
//
//    'email-footer' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
//    'email-footer_cell' => 'color: #AEAEAE; padding: 35px; text-align: center;',

    /* Body ------------------------------ */

//    'body_action' => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
//    'body_sub' => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',

    /* Type ------------------------------ */

//    'anchor' => 'color: #3869D4;',
//    'header-1' => 'margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;',
//    'paragraph' => 'margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;',
//    'paragraph-sub' => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
//    'paragraph-center' => 'text-align: center;',

    /* Buttons ------------------------------ */

//    'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
//                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
//                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',
//
//    'button--green' => 'background-color: #22BC66;',
//    'button--red' => 'background-color: #dc4d2f;',
//    'button--blue' => 'background-color: #3869D4;',

    'white_link' => 'text-decoration: none; color: white;'
];
?>

<?php
$fontFamily = 'font-family: "Avenir",Helvetica,Arial,sans-serif;';
?>

<body style="{{ $style['body'] }} {{ $fontFamily }}">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" style="height: 50px;">
            <table style="{{ $style['wrapped'] }} color: white;" width="500px" cellpadding="0" cellspacing="0">
                @yield('top')
            </table>
        </td>
    </tr>
    <tr>
        <td style="{{ $style['email-wrapper'] }}" align="center">
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
</body>
</html>
