@extends('layouts.app')

@section('content')
    <div class="container semantic">

        <div class="row">
            <div class="col-md-12">

                <my-vuetable></my-vuetable>

                @if (noListingForUser())
                    @if (issetAmazonMwsForUser())
                        <!-- Autoload the page after 10 seconds -->
                        <script>setTimeout(function(){ window.location.reload(1); }, 10000);</script>
                        <div class="alert alert-default }}">We are currently requesting your listing from Amazon using <strong>{{ link_to_route('settings.index', 'your keys') }}</strong>.</div>
                    @else
                        <div class="alert alert-warning">Amazon keys not set or set incorrectly.  {{ link_to_route('settings.index', 'Enter you Amazon keys here') }}.</div>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection
