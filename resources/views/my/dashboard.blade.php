@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">My Dashboard</div>

                    <div class="panel-body">

                        @if (session()->has('message'))
                            <div class="alert alert-{{ session('message_style') }}">{{ session('message') }}</div>
                        @endif

                        <listing head="Amazon Inventory">
                            <template slot="amazon-requesting">
                                We are currently requesting your listing from Amazon using <strong>{{ link_to_route('settings.index', 'your keys') }}</strong>.
                            </template>
                            <template slot="amazon-mws-link">
                                {{ link_to_route('settings.index', 'Click here to enter your Amazon keys.') }}
                            </template>
                        </listing>

                        @if (issetAmazonMwsForUser() && false)
                            <!-- Autoload the page after 10 seconds -->
                            <script>setTimeout(function(){ window.location.reload(1); }, 10000);</script>
                            <div class="alert alert-default }}">We are currently requesting your listing from Amazon using <strong>{{ link_to_route('settings.index', 'your keys') }}</strong>.</div>
                        @endif

                        @if (false)
                            <div class="alert alert-warning">Amazon keys not set or set incorrectly.  {{ link_to_route('settings.index', 'Enter you Amazon keys here') }}.</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
