@extends('layouts.report')

@section('top')
    <tr>
        <td class="text-right">
            <label>Inclusive Dates: <input type="text" id="daterange" name="daterange"></label>
        </td>
    </tr>
@endsection

@section('content')
    <tr>
        <td>
            <h1>{!! $reportTitle !!}</h1>
            <p style="text-align: center;">{{ pluralize('violation', count($reportData['rows'])) }}</p>
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
                    let url = '/my/reports/offer-violations/'
                        + start.format('YYYY-MM-DD')
                        + '/'
                        + end.format('YYYY-MM-DD')

                    window.location = url
                }
            );
        });
    </script>
@endsection
