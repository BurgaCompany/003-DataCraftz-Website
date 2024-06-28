@extends('layouts/main')

@section('container')
<div class="lime-container">
    <div class="lime-body">
        <div class="container">
            <div class="row">
                <div class="col-xl">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="card-title mb-4" style="font-size: 20px;">Review Sopir : {{ $driver->name }}</h2>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="alert alert-info mb-0" role="alert">
                                    <span><strong>Rating:</strong></span>
                                    <span id="available-chairs">
                                        @php
                                            $integerPart = floor($averageRating); // Bagian integer dari rating
                                            $fractionPart = $averageRating - $integerPart; // Bagian pecahan dari rating
                                        @endphp
                            
                                        {{-- Tampilkan bintang penuh --}}
                                        @for ($i = 1; $i <= $integerPart; $i++)
                                            <span><i class="fas fa-star" style="color: gold;"></i></span>
                                        @endfor
                            
                                        {{-- Tampilkan bintang setengah jika ada --}}
                                        @if ($fractionPart > 0 && $integerPart < 5)
                                            <span><i class="fas fa-star-half-alt" style="color: gold;"></i></span>
                                            @php $integerPart++; @endphp
                                        @endif
                            
                                        {{-- Tampilkan bintang kosong untuk mencapai 5 bintang --}}
                                        @for ($i = $integerPart + 1; $i <= 5; $i++)
                                            <span><i class="fas fa-star" style="color: lightgray;"></i></span>
                                        @endfor
                            
                                        <strong>({{ number_format($averageRating, 1) }})</strong>
                                    </span>
                                </div>
                            </div>
                            
                            

                            <div class="row">
                                @if ($reviews->isEmpty())
                                    <div class="col-12 text-center">
                                        <div class="alert alert-warning" role="alert">
                                            Data kosong atau tidak ada data
                                        </div>
                                    </div>
                                @else
                                    @foreach($reviews as $review)
                                    <div class="col-md-6 mb-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title">Review</h5>
                                                <div class="d-flex align-items-center mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fas fa-star" style="color: gold;"></i>
                                                        @else
                                                            <i class="fas fa-star" style="color: lightgray;"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-2">
                                                        <strong>({{ number_format($review->rating, 1) }})</strong>
                                                    </span>                                                    
                                                </div>
                                                <p class="card-text">{{ $review->review }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
