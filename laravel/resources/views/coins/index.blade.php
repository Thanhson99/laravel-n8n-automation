@extends('layouts.app')

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
                    <th>Favourite</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coins as $coin)
                    <tr>
                        <td>
                            <a href="{{ route('coins.show', ['symbol' => $coin['symbol']]) }}">
                                {{ $coin['symbol'] }}
                            </a>
                        </td>
                        <td>${{ number_format($coin['lastPrice'] ?? 0, 3) }}</td>
                        <td>${{ number_format($coin['quoteVolume'] ?? 0, 3) }}</td>
                        <td>{{ $coin['priceChangePercent'] ?? '-' }}</td>
                        <td>
                            <a href="#" class="favorite-toggle" data-symbol="{{ $coin['symbol'] }}">
                                <i class="fa-heart fa {{ in_array($coin['symbol'], $favorites ?? []) ? 'fas text-danger' : 'far text-muted' }}"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('page_js')
    <script>
        const toggleFavoriteUrl = @json(route('favorites.toggle'));

        $(function () {
            $('.favorite-toggle').on('click', function (e) {
                e.preventDefault();

                const symbol = $(this).data('symbol');
                const icon = $(this).find('i');

                $.ajax({
                    url: toggleFavoriteUrl,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    data: {
                        symbol: symbol
                    },
                    success: function (response) {
                        if (response.success) {
                            if (icon.hasClass('fas')) {
                                icon.removeClass('fas text-danger').addClass('far text-muted');
                            } else {
                                icon.removeClass('far text-muted').addClass('fas text-danger');
                            }
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message || 'Something went wrong.');
                        }
                    },
                    error: function () {
                        toastr.error('Server error. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush

