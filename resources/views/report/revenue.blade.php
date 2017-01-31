@extends('layouts.report')

@section('top')
    <tr>
        <td class="text-right p-tb-20">
            <label>Inclusive Dates: <input type="text" id="daterange" name="daterange"></label>
        </td>
    </tr>
@endsection

@section('content')
    <tr>
        <td>
            <h1 style="color: #87d9bf; text-align: center; margin: 0 0 10px; font-size: 1.5em;">DAILY REVENUE:</h1>
            <div class="total-amount">${{ $reportData['summary']->total_amount ?: 0 }}</div>
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
                            <td style="border: 1px solid #e7f0ee; padding: 0.5rem 0.5rem 0.5rem 1.5rem;">
                                <a href="https://amzn.com/{{ $row->asin }}" target="_blank" title="See item in Amazon">
                                    {{ $row->asin }}
                                </a>
                            </td>
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

@section('bottom')
    <script type="text/javascript">
        $(function() {
            $('input[name="daterange"]').daterangepicker(
                {
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                    startDate: '{{ $startYmd }}',
                    endDate: '{{ $endYmd }}'
                },
                function(start, end, label) {
                    let url = '/my/reports/revenue/'
                        + start.format('YYYY-MM-DD')
                        + '/'
                        + end.format('YYYY-MM-DD')

                    window.location = url
                }
            );
        });
    </script>
@endsection
