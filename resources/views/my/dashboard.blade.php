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

                        @if (count($products))
                            <div class="table-responsive">
                                <h3>Amazon Inventory</h3>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Seller SKU</th>
                                            <th>ASIN</th>
                                            <th class="text-center">Total Supply Quantity</th>
                                            <th>FNSKU</th>
                                            <th>Condition</th>
                                            <th class="text-center">In Stock Supply Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($inventory as $item)
                                        <tr>
                                            <td>{{ $item['SellerSKU'] }}</td>
                                            <td>{{ $item['ASIN'] }}</td>
                                            <td class="text-right">{{ $item['TotalSupplyQuantity'] }}</td>
                                            <td>{{ $item['FNSKU'] }}</td>
                                            <td>{{ $item['Condition'] }}</td>
                                            <td class="text-right">{{ $item['InStockSupplyQuantity'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning }}">Amazon keys not set or set incorrectly.  {{ link_to_route('settings.index', 'Enter you Amazon keys here.') }}.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
