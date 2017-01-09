@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <h1>Amazon MWS</h1>
                <p>Requires Amazon Professional Selling account, {{ link_to('#', 'read more about account types') }}.
                <br>Need help getting setup?  {{ link_to('#', 'Contact Us') }}</p>

                <div class="widget clearfix m-t-40">
                    <div class="widget-left-container">
                        <ol class="li-m-b-10 font-em-05">
                            <li>{{ link_to('#', 'Visit Amazon Marketplace Web Services') }} (Amazon MWS) and click on yellow button "Sign up for MWS" on the right side.</li>
                            <li>Choose the 3rd option "I want to give a developer access to my Amazon seller account with MWS.</li>
                            <li>For "Application Name" enter <strong>SkuBright</strong> and for "Application's Account Number" enter <strong>1234-5678-1234</strong></li>
                            <li>Click next, then copy and paste the data into these fields on the right.</li>
                        </ol>
                    </div>
                    <div class="widget-right-container">
                        @include('my._form_mws_settings')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
