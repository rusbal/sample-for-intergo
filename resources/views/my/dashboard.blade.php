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

                        @if (count($listing))
                            <div class="table-responsive">
                                <h3 class="pull-left">Amazon Inventory</h3>
                                <div class="pull-right">Total: {!! count($listing) !!}</div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Seller SKU</th>
                                            <th>ASIN</th>
                                            <th>Listing ID</th>
                                            <th>Item Name</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($listing as $item)
                                        <tr>
                                            <td>{{ $item['seller_sku'] }}</td>
                                            <td>
                                                <a href="{{ amazonOfferLink($item['asin1']) }}" target="_blank">{{ $item['asin1'] }}</a>
                                            </td>
                                            <td>{{ $item['listing_id'] }}</td>
                                            <td>{{ $item['item_name'] }}</td>
                                            <td>{!! str_limit($item['item_description'], 100) !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @if (issetAmazonMwsForUser())
                                <!-- Autoload the page after 10 seconds -->
                                <script>setTimeout(function(){ window.location.reload(1); }, 10000);</script>
                                <div class="alert alert-default }}">We are currently requesting your listing from Amazon using <strong>{{ link_to_route('settings.index', 'your keys') }}</strong>.</div>
                            @else
                                <div class="alert alert-warning }}">Amazon keys not set or set incorrectly.  {{ link_to_route('settings.index', 'Enter you Amazon keys here') }}.</div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
