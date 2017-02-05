@extends('emails.layouts.report')

@section('top')
    <tr>
        <td style="text-align:left; padding: 20px;">
            <a style="text-decoration: none; color: white;" href="{{ url('/my/reports/offer-violations') }}">VIEW IN BROWSER</a>
        </td>
        <td style="text-align:right; padding: 20px;">{{ date('n/d/y') }}</td>
    </tr>
@endsection

@section('content')
    <tr>
        <td style="text-align: center;">
            <h1 style="color: #87d9bf; margin: 0 0 10px; font-size: 1.5em;">OFFER VIOLATIONS</h1>
            @if (count($reportData['rows']) == $reportData['count'])
                <p>{{ count($reportData['rows']) }} violations</p>
            @else
                <p>{{ count($reportData['rows']) }} of {{ $reportData['count'] }} violations</p>
            @endif
        </td>
    </tr>
    <tr>
        <td>
            <table style="margin: 20px; padding: 40px; width: calc(100% - 40px); border-collapse: collapse;">
                <thead>
                <tr style="background-color: #3f4041; color: white;">
                    <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">Item</th>
                    <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">ASIN</th>
                    <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">Offers</th>
                    <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">Maximum</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($reportData['rows'] as $i => $row)
                    <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#f0f9f7' }};">
                        <td style="border: 1px solid #e7f0ee; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">{{ $row->item_name ?: '&mdash;' }}</td>
                        <td style="border: 1px solid #e7f0ee; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">
                            <a href="https://amzn.com/{{ $row->asin }}" target="_blank" title="See item in Amazon">
                                {{ $row->asin }}
                            </a>
                        </td>
                        <td style="text-align: center; border: 1px solid #e7f0ee; padding: 0.5rem;">{{ $row->offer_quantity }}</td>
                        <td style="text-align: center; border: 1px solid #e7f0ee; padding: 0.5rem;">{{ $row->maximum_offer_quantity }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 1em;">
            <hr style="width: 40px; height: 3px; background-color: #87d9bf; border: none;">
        </td>
    </tr>
@endsection
