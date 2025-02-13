@extends('web.default.master')
@section('body')
<div class="page-content">
    <!--banner-->
    <div class="dz-bnr-inr style-1" style="background-image:url({{asset('web/images/background/bg-shape.jpg')}});">
        <div class="container">
            <div class="dz-bnr-inr-entry">
                <h1>Our Products</h1>
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"> Home</a></li>
                        <li class="breadcrumb-item">Products</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <section class="content-inner-1 pt-3 z-index-unset">
        <div class="container">
            <div class="row">
                @include("web.default.product_filter")
                <div class="col-xl-9 col-lg-12">

                    <div class="row">
                        <div class="row gx-xl-4 g-3 mb-xl-0 mb-md-0 mb-3">
                            @foreach($products as $product)
                            <li class="card-container col-6 col-xl-3 col-lg-3 col-md-4 col-sm-6 Begs mt-5" >
                                <div class="shop-card">
                                    <a href="/products/{{$product->id}}/detail">
                                    <div class="dz-media">
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-height:220px !important" loading="lazy">
                                       
                                    </div>
                                    <div class="dz-content">
                                        <h5 class="title">{{ $product->name }}</h5>
                                        @php
                                                        $currencyCode = session('currency', 'USD');
                                                        $currency = App\Models\Currency::where('code', $currencyCode)->first();
                                                        $currencySymbol = $currency->symbol ?? '$';
                                                        $exchangeRate = $currency->exchange_rate ?? 1;
                                                    @endphp
                                        <h6 class="price" style="color:black !important;">
                                        @if($product->product_type == 1)
                                                @if($product->discount_type == 0)
                                                {{ $currencySymbol }}
                                                {{ number_format($product->price * $exchangeRate, 2) }}
                                                @elseif($product->discount_type == 1)
                                                @php
                                                $discountPrice = ($product->price - $product->discount_amount) *
                                                $exchangeRate;
                                                @endphp
                                                <del>{{ $currencySymbol }}
                                                    {{ number_format($product->price * $exchangeRate, 2) }}</del>
                                                {{ $currencySymbol }} {{ number_format($discountPrice, 2) }}
                                                @elseif($product->discount_type == 2)
                                                @php
                                                $discountPrice = ($product->price - ($product->price *
                                                ($product->discount_amount / 100))) * $exchangeRate;
                                                @endphp
                                                <del>{{ $currencySymbol }}
                                                    {{ number_format($product->price * $exchangeRate, 2) }}</del>
                                                {{ $currencySymbol }} {{ number_format($discountPrice, 2) }}
                                                @endif
                                                @elseif($product->product_type == 2)
                                                @php
                                                $minPrice = $product->variants->min('price') * $exchangeRate;
                                                $maxPrice = $product->variants->max('price') * $exchangeRate;
                                                @endphp
                                                {{ $currencySymbol }} {{ number_format($minPrice, 2) }} ~
                                                {{ $currencySymbol }} {{ number_format($maxPrice, 2) }}
                                                @endif
                                        </h6>
                                    </div>
                                    @if($product->discount_type != 0)
                                    <div class="product-tag">
                                        <span class="badge badge-secondary">Sale |
                                            @if($product->discount_type == 1)
                                            {{$product->discount_amount}} $ OFF
                                            @elseif($product->discount_type == 2)
                                            {{$product->discount_amount}} % OFF
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                    @if($product->pre_order == 1)
                                    <div class="product-tag">
                                        <span class="badge badge-info">
                                            Pre Order
                                        </span>
                                    </div>
                                    @endif
                                    </a>
                                </div>
                              
                            </li>
                            @endforeach


                        </div>
                    </div>
                    <!-- Custom Pagination -->
                    <div class="row page mt-0">
                        <div class="col-md-6">
                            <p class="page-text">
                                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of
                                {{ $products->total() }} Results
                            </p>
                        </div>
                        <div class="col-md-6">
                            <nav aria-label="Blog Pagination">
                                <ul class="pagination style-1">
                                    <!-- Previous Page Link -->
                                    @if ($products->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link prev">Prev</span></li>
                                    @else
                                    <li class="page-item"><a class="page-link prev"
                                            href="{{ $products->previousPageUrl() }}">Prev</a></li>
                                    @endif

                                    <!-- Pagination Elements -->
                                    @foreach ($products->links()->elements[0] as $page => $url)
                                    @if ($page == $products->currentPage())
                                    <li class="page-item active" ><span class="page-link" style="background-color:black !important;color:white !important;">{{ $page }}</span></li>
                                    @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                    @endforeach

                                    <!-- Next Page Link -->
                                    @if ($products->hasMorePages())
                                    <li class="page-item"><a class="page-link next"
                                            href="{{ $products->nextPageUrl() }}">Next</a></li>
                                    @else
                                    <li class="page-item disabled"><span class="page-link next">Next</span></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
    </section>

    <!-- Icon Box Start -->
    @include("web.default.product_footer")
    <!-- Icon Box End -->
</div>
@endsection