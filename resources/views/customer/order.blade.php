@extends('layouts.customer')

@section('body')
<style>

</style>
@if($orders->count() > 1)
<div class="container mt-4">
    <div class="row">
       
        @foreach($orders as $order)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Order #{{ $order->order_number }}</h5>
                    <p class="card-text">Total Price: {{ $order->payment_currency_price + $order->delivery_price	 }}
                        {{ $order->payment_currency	 }}</p>
                    <p class="card-text">
                        Status:
                        @if($order->status == 1)
                        <span class="badge badge-warning">Pending</span>
                        @elseif($order->status == 2)
                        <span class="badge badge-success">Confirmed</span>
                        @elseif($order->status == 3)
                        <span class="badge badge-danger">Cancelled</span>
                        @elseif($order->status == 4)
                        <span class="badge badge-warning">Paymemt Pending</span>
                        @endif
                    </p>
                    <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
        @endforeach

       
    </div>
</div>
@else

<div class="row d-flex justify-content-center align-items-center" style="height: 50vh;">
    <div class="col-12 col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="text-muted">You haven't placed any orders yet!</h3>
                <p class="text-secondary">Browse our collection and find something you love.</p>
                <a href="/products" class="btn btn-primary mt-3">Start Shopping</a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection