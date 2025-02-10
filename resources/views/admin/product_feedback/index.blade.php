@extends('layouts.admin')

@section('body')
<div class="card">
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> Please check the form below for errors.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h4 class="card-title">Product Feedback List</h4>

        <div class="m-t-25">
            <table id="data-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product</th>
                        <th>User</th>
                        <th>Star</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productFeedbacks as $index => $productFeedback)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($productFeedback->product->product_type == 1)
                            <a target="_blank" href="/admin/admin/product/{{$productFeedback->product->id}}/detail/v1">
                                {{ $productFeedback->product->name ?? 'N/A' }}</a>
                            @elseif($productFeedback->product->product_type == 2)

                            <a target="_blank" href="/admin/admin/product/{{$productFeedback->product->id}}/detail/v2">
                                {{ $productFeedback->product->name ?? 'N/A' }}</a>

                            @endif
                            <br>
                            <img src="{{ asset($productFeedback->product->image  ?? '' ) }}" alt="Product  Image"
                                style="width:80px;height:80px !important;">

                        </td>
                        <td>
                            @if($productFeedback->user)
                            <button type="button" class="btn btn-default" data-toggle="modal"
                                data-target="#customerDetailModal-{{ $productFeedback->user->id }}">
                                {{ $productFeedback->user->name ?? 'N/A' }}
                            </button>
                            @else
                            <span>Guest User</span>
                            @endif

                            @if ( $productFeedback->user)
                            <!-- Customer Detail Modal -->
                            <div class="modal fade" id="customerDetailModal-{{  $productFeedback->user->id  }}" tabindex="-1"
                                role="dialog" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="customerDetailModalLabel">Customer Details:
                                                {{  $productFeedback->user->name  }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Email: {{ $productFeedback->user->email }}</h6>
                                            <h6>Phone: {{ $productFeedback->user->phone }}</h6>
                                            <h6>Orders Count:
                                                {{ App\Models\Order::where("user_id", $productFeedback->user->id)->count() }}</h6>
                                            <h6>Orders Total Amount:
                                                {{ number_format(App\Models\Order::where("user_id", $productFeedback->user->id)->sum("total_price"), 1) }}
                                                $</h6>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Order No</th>
                                                        <th>Price</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $userOrders = App\Models\Order::where("user_id", $productFeedback->user->id)
                                                    ->orderBy("id", "desc")
                                                    ->get(["order_number", "total_price", "created_at"]);
                                                    @endphp

                                                    @foreach($userOrders as $userOrder)
                                                    <tr>
                                                        <td>{{ $userOrder->order_number }}</td>
                                                        <td>{{ number_format($userOrder->total_price, 1) }} $</td>
                                                        <td>{{ $userOrder->created_at->format('F j, Y') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td>{{ $productFeedback->review_star }}/5</td>
                        <td>{{ $productFeedback->message }}</td>
                        <td>
                            @if($productFeedback->status == 1)
                            <span class="badge badge-success">Approved</span>
                            @else
                            <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.feedback.show', $productFeedback->id) }}"
                                class="btn btn-primary btn-sm">  <i class="anticon anticon-eye"></i></a>
                            <form action="{{ route('admin.feedback.destroy', $productFeedback->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')"><i class="anticon anticon-delete"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection