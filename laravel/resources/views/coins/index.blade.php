@extends('adminlte::page')

@section('title', 'Top Coins')

@section('content_header')
    <h1>Top Coins (Source: {{ ucfirst($source) }})</h1>
@endsection

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Price</th>
                    <th>Volume</th>
                    <th>Change (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coins as $coin)
                    <tr>
                        <td>{{ $coin['symbol'] }}</td>
                        <td>{{ $coin['lastPrice'] ?? '-' }}</td>
                        <td>{{ $coin['quoteVolume'] ?? '-' }}</td>
                        <td>{{ $coin['priceChangePercent'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
