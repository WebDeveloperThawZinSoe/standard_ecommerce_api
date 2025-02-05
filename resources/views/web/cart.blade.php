@extends('web.master')
@section('body')
<style>
@media (max-width: 768px) {
    .product-item-name a {
        font-size: 0.9rem;
    }

    .product-item-quantity input {
        max-width: 50px;
    }
}

#coupon {
    border: 1px solid black;
}
</style>

<div class="page-content">
    <!-- Contact Area -->
    <section class="content-inner shop-account">
        <!-- Product -->
        <div class="container">
            <div class="row">
                <!-- Cart Table -->
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table check-tbl">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none d-md-table-cell"></th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="d-none d-md-table-cell">Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $cards = auth()->check()
                                ? App\Models\Card::where('user_id', auth()->id())->with('product_variant')->get()
                                : App\Models\Card::where('session_id',
                                session()->getId())->with('product_variant')->get();

                                $totalPrice = 0;

                                $card = auth()->check()
                                ? App\Models\Card::where('user_id', auth()->id())->with('product_variant')->first()
                                : App\Models\Card::where('session_id',
                                session()->getId())->with('product_variant')->first();

                                $cupon_code = $card ? $card->coupon_code : null;
                                @endphp
                                @foreach($cards as $card)
                                @php
                                $variantProductPrice = $card->product_variant->price;
                                $variantProductPriceOrg = $card->product_variant->price;
                                $vDiscountType = $card->product_variant->discount_type;
                                $vDiscountAmount = $card->product_variant->discount_amount;

                                if ($vDiscountType == 0) {
                                $finalPrice = $variantProductPrice;
                                } elseif ($vDiscountType == 1) {
                                $finalPrice = $variantProductPrice - $vDiscountAmount;
                                } elseif ($vDiscountType == 2) {
                                $finalPrice = $variantProductPrice - ($variantProductPrice * ($vDiscountAmount / 100));
                                }
                                $subtotal = $finalPrice * $card->qty;
                                $totalPrice += $subtotal;
                                @endphp
                                <tr>
                                    <td class="product-item-img">
                                        <div class="product-item-name d-block d-md-none mb-2">
                                            <a href="/products/{{ $card->product_variant->product->id }}/detail">
                                                {{ $card->product_variant->product->name }}
                                            </a>
                                        </div>
                                        <img src="{{ asset($card->product_variant->image) }}" alt="/" class="img-fluid">
                                    </td>
                                    <td class="product-item-name d-none d-md-table-cell">
                                        <a href="/products/{{ $card->product_variant->product->id }}/detail">
                                            {{ $card->product_variant->product->name }}
                                            ({{ $card->product_variant->attribute_name }} -
                                            {{ $card->product_variant->attribute_value }})
                                        </a>
                                    </td>
                                    <td class="product-item-price">
                                        @php
                                        $currencyCode = session('currency', 'USD');
                                        $currency = App\Models\Currency::where('code', $currencyCode)->first();

                                        $currencySymbol = $currency->symbol ?? '$';
                                        $exchangeRate = $currency->exchange_rate ?? 1;
                                        $variantProductPrice = $variantProductPrice * $exchangeRate;
                                        @endphp
                                        @if ($vDiscountType == 0)
                                        {{ number_format($variantProductPrice, 2) }} {{$currencySymbol}}
                                        @else
                                        @php
                                        $variantProductPriceOrg = $variantProductPriceOrg * $exchangeRate;
                                        $finalPrice = $finalPrice * $exchangeRate;
                                        @endphp
                                        <del>{{ number_format($variantProductPriceOrg, 2) }}</del>
                                        {{ number_format($finalPrice, 2) }} {{$currencySymbol}}
                                        @endif
                                    </td>
                                    <td class="product-item-quantity">
                                        <div class="quantity btn-quantity style-1 me-3 d-flex align-items-center">
                                            <form action="{{ route('cart.update', $card->id) }}" method="POST"
                                                class="me-2">
                                                @csrf
                                                <input type="hidden" name="action" value="sub">
                                                <button type="submit" class="btn btn-sm btn-secondary">-</button>
                                            </form>
                                            <input id="demo_vertical7" type="text" value="{{ $card->qty }}" readonly
                                                class="form-control">
                                            <form action="{{ route('cart.update', $card->id) }}" method="POST"
                                                class="ms-2">
                                                @csrf
                                                <input type="hidden" name="action" value="add">
                                                <button type="submit" class="btn btn-sm btn-secondary">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="product-item-totle d-none d-md-table-cell">
                                        @php
                                        $subtotal = $subtotal * $exchangeRate;
                                        @endphp
                                        {{ number_format($subtotal, 2) }} {{$currencySymbol}}
                                    </td>
                                    <td class="product-item-close">
                                        <form action="/cart/remove/{{ $card->id }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $card->id }}" name="id">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="ti-close"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cart Total -->
                <div class="col-lg-4">
                    <h4 class="title mb15">Cart Total</h4>
                    <div class="cart-detail">
                        <div class="icon-bx-wraper style-4 m-b30">
                            <div class="icon-content">
                                <h6 class="dz-title">Delivery Fee </h6>
                                <p>
                                    @php
                                        $currencyCode = session('currency', 'USD');
                                        $currency = App\Models\Currency::where('code', $currencyCode)->first();

                                        $currencySymbol = $currency->symbol ?? '$';
                                        $exchangeRate = $currency->exchange_rate ?? 1;
                                    @endphp
                                    @php

                                    $delivery = App\Models\Delivery::where("currency",$currencyCode)->first();
                                    $deli_price = $delivery->deli_price;
                                    $mini_price = $delivery->mini_price;
                                    @endphp
                                    Delivery Fee is {{$deli_price}} {{$currencySymbol}} for orders below {{$mini_price}}
                                    {{$currencySymbol}} , <b> over {{$mini_price}} {{$currencySymbol}} is free </b>.
                                </p>
                            </div>
                        </div>

                        @if($cupon_code)
                        @php
                        $cupon_code_info = App\Models\CuponCode::where("id",$cupon_code)->first();
                        $cupon_code = $cupon_code_info->cupon_code;
                        $cupon_description = $cupon_code_info->description;

                        @endphp
                        <!-- Coupon Section (Coupon Already Applied) -->
                        <form action="{{ route('cart.apply_coupon') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="coupon" class="form-label">Coupon Received</label>
                                <input type="text" name="coupon" id="coupon"
                                    class="form-control @error('coupon') is-invalid @enderror"
                                    placeholder="Enter your coupon code" value="{{ $cupon_code }}" readonly>

                                <!-- Coupon Description -->
                                <p class="mt-2 text-success" style="color:green !important;">{!! $cupon_description !!}
                                </p>

                                <!-- Custom Coupon Code Error Message -->
                                @if(session('coupon_error'))
                                <div class="text-danger mt-2">{{ session('coupon_error') }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary w-100" disabled>Coupon Applied</button>

                        </form>

                        <br>
                        <form action="{{ route('cart.coupon.remove') }}" method="post">
                            @csrf
                            <input type="submit" class="btn btn-danger w-100" value="Remove Coupon Code">
                        </form>
                        @else
                        <!-- Coupon Section (No Coupon Applied) -->
                        <form action="{{ route('cart.apply_coupon') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="coupon" class="form-label">Coupon Received</label>
                                <input type="text" name="coupon" id="coupon"
                                    class="form-control @error('coupon') is-invalid @enderror"
                                    placeholder="Enter your coupon code" value="{{ old('coupon') }}">

                                <!-- Validation Error Message -->
                                @error('coupon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- Custom Coupon Code Error Message -->
                                @if(session('coupon_error'))
                                <div class="text-danger mt-2">{{ session('coupon_error') }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Apply Coupon</button>
                        </form>
                        @endif





                        <table>
                            <tbody>
                                <br>
                                <tr class="delivery">
                                    <td>
                                        <h6 class="mb-0">Delivery Fee</h6>
                                    </td>

                                    <td class="price" style="text-align:right">
                                        @php
                                        $cupon_code = $card ? $card->coupon_code : null;
                                        $original_price = $totalPrice;
                                        $after_discount_price = $totalPrice; // Initialize with the total price

                                        if ($cupon_code) {
                                        $cupon_code_info = App\Models\CuponCode::find($cupon_code);
                                        if ($cupon_code_info) {
                                        $cupon_code_type = $cupon_code_info->type;
                                        $cupon_code_amount = $cupon_code_info->amount;

                                        if ($cupon_code_type == 1) {
                                        $after_discount_price -= $cupon_code_amount;
                                        } elseif ($cupon_code_type == 2) {
                                        $after_discount_price -= ($after_discount_price * ($cupon_code_amount / 100));
                                        }
                                        }
                                        }

                                        // Apply exchange rate
                                        $original_price *= $exchangeRate;
                                        $after_discount_price *= $exchangeRate;

                                        // Determine final order price (with or without coupon)
                                        $order_price = $cupon_code ? $after_discount_price : $original_price;

                                        // Fetch delivery settings
                                        $delivery = App\Models\Delivery::where("currency", session("currency",
                                        "USD"))->first();
                                        $deliveryFee = 0;

                                        if ($delivery) {
                                        $deliveryFee = ($order_price < $delivery->mini_price) ? $delivery->deli_price :
                                            0;
                                            
                                            }
                                            @endphp

                                            @if ($deliveryFee > 0)
                                            {{ number_format($deliveryFee, 2) }} {{ $currencySymbol }}
                                            @else
                                            Delivery Fee is Free
                                            @endif
                                    </td>
                                </tr>

                                <tr class="total">
                                    <td>
                                        <h6 class="mb-0">Total</h6>
                                    </td>

                                    @php
                                    // Add delivery fee to total price
                                    $finalTotal = $order_price + $deliveryFee;
                                    @endphp

                                    <td class="price">
                                        @if ($cupon_code)
                                        <del>{{ number_format($original_price, 2) }} {{ $currencySymbol }}</del>
                                        <br>
                                        {{ number_format($finalTotal, 2) }} {{ $currencySymbol }}
                                        @else
                                        {{ number_format($finalTotal, 2) }} {{ $currencySymbol }}
                                        @endif
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                        @if($cards->count() > 0)
                        <a href="/checkout" class="btn btn-secondary w-100">PLACE ORDER</a>
                        @else
                        <a href="/products" class="btn btn-secondary w-100">ADD TO CART FIRST</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include("web.product_footer")
</div>
@endsection