@extends('layouts.report')

@section('top')
    <tr>
        <td style="text-align:left; padding: 20px;">
            <a style="text-decoration: none; color: white;" href="{{ url('/my/reports/daily-revenue') }}">VIEW IN BROWSER</a>
        </td>
        <td style="text-align:right; padding: 20px;">{{ date('n/d/y', time() - 86400) }}</td>
    </tr>
@endsection

@section('content')
    <tr>
        <td>
            <h1 style="color: #87d9bf; text-align: center; margin: 0 0 10px; font-size: 1.5em;">DAILY REVENUE:</h1>
            <h2 style="text-align: center; padding: 25px; background: #f0f9f7; font-size: 2.5em; width: 250px; border: 1px solid #e7f0ee; margin: 0 auto; border-radius: 5px;">{{ $reportData['summary']->total_amount }}</h2>
        </td>
    </tr>
    <tr>
        <td>
            <table style="margin: 20px; padding: 40px; width: calc(100% - 40px); border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #3f4041; color: white;">
                        <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">Item</th>
                        <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">ASIN</th>
                        <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">Qty</th>
                        <th style="border: 1px solid #e7f0ee; padding: 0.5rem;">$</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reportData['rows'] as $i => $row)
                        <tr style="background-color: {{ $i % 2 == 0 ? '#ffffff': '#f0f9f7' }};">
                            <td style="border: 1px solid #e7f0ee; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">{{ $row->item ?: '&mdash;' }}</td>
                            <td style="border: 1px solid #e7f0ee; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">{{ $row->asin }}</td>
                            <td style="text-align: center; border: 1px solid #e7f0ee; padding: 0.5rem;">{{ $row->quantity }}</td>
                            <td style="text-align: center; border: 1px solid #e7f0ee; padding: 0.5rem;">
                                ${{ number_format($row->amount, 2) }}
                            </td>
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
    <tr>
        <td style="text-align: center; font-size: 0.8em; padding: 1em 0 4em;">
            {{ config('app.name') }}. {{ config('app.address_line_1') }}<br>
            {{ config('app.address_line_2') }}
        </td>
    </tr>
@endsection

