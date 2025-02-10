@extends('layouts.admin')

@section('body')
<style>
/* Toggle Switch Style */
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 25px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 25px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 4px;
    bottom: 3.5px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked+.slider {
    background-color: #28a745;
}

input:checked+.slider:before {
    transform: translateX(25px);
}
</style>
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

        <h4 class="card-title">Product Feedback </h4>
        <br>

        <div class="row">
            <div class="col-md-6">
                <h4>Product Information</h4>
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
            </div>
            <div class="col-md-6">
                <h4>Reviewer Information</h4>
                @if($productFeedback->user)
                <button type="button" class="btn btn-default" data-toggle="modal"
                    data-target="#customerDetailModal-{{ $productFeedback->user->id }}">
                    {{ $productFeedback->user->name ?? 'N/A' }}
                </button>
                @else
                <span>Guest User</span>
                <br>
                <?php
                    $data = json_decode($productFeedback->user_information_data, true);

                    // Extract values
                    $ip = $data['IP'] ?? 'Unknown';
                    $userAgent = $data['User-Agent'] ?? 'Unknown';

                    // Detect browser from User-Agent
                    if (strpos($userAgent, 'Chrome') !== false) {
                        $browser = 'Google Chrome';
                    } elseif (strpos($userAgent, 'Firefox') !== false) {
                        $browser = 'Mozilla Firefox';
                    } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
                        $browser = 'Apple Safari';
                    } elseif (strpos($userAgent, 'Edge') !== false) {
                        $browser = 'Microsoft Edge';
                    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
                        $browser = 'Internet Explorer';
                    } else {
                        $browser = 'Unknown Browser';
                    }

                    // Display nicely
                    echo "<p><strong>IP Address:</strong> $ip</p>";
                    echo "<p><strong>User Agent:</strong> $userAgent</p>";
                    echo "<p><strong>Browser:</strong> $browser</p>";
                ?>

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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>


        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <h4>Rating</h4>
                <p>{{ $productFeedback->rating ?? 'N/A' }}</p>
                <h4>Message</h4>
                <p>{{ $productFeedback->message ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h4>Feedback Date</h4>
                <p>{{ $productFeedback->created_at->format('F j, Y') }}</p>
                <h4>Status Date</h4>
                <p>
                    @if($productFeedback->status == 1)
                    <span class="badge badge-success">Approved</span>
                    @else
                    <span class="badge badge-warning">Pending</span>
                    @endif
                </p>
                <hr>
                <p class="row">
                    <label for="">Change The Status :  &nbsp; &nbsp;</label>
                    <label class="switch">
                        
                        <input type="checkbox" id="statusToggle" data-id="{{ $productFeedback->id }}"
                            {{ $productFeedback->status == 1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                     
                    </label>
                  
                </p>

                <script>
                document.getElementById('statusToggle').addEventListener('change', function() {
                    let feedbackId = this.getAttribute('data-id');
                    let newStatus = this.checked ? 1 : 0;

                    fetch(`/admin/feedback/status/${feedbackId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('statusText').textContent = data.status ? 'Approved' :
                                'Pending';


                        })
                        .catch(error => console.error('Error:', error));

                        setTimeout(() => {
                            location.reload();
                        }, 1000); // 
                });
                </script>

            </div>
        </div>



    </div>
</div>
@endsection