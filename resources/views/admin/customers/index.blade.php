@extends('layouts.admin')
@section('body')

<div class="card">
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-primary alert-dismissible fade show">
            <strong>Success!</strong> {{ session('status') }}
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
        <button class="btn btn-primary btn-tone m-r-5" data-toggle="modal" data-target="#createModal"><i
                class="anticon anticon-plus"></i> Create</button>

        <div class="m-t-25">
            <table id="data-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Order</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key=>$user)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>{{ $user->customerType->type->name ?? '-' }}</td>
                        <td>
                            @php
                            $order_count = App\Models\Order::where("user_id",$user->id)->count();
                            if($order_count != 0){
                            echo $order_count;
                            }else{
                            echo "-";
                            }

                            @endphp

                        </td>
                        <td>
                            {{ $user->created_at->format('F j, Y, g:i a') }}
                        </td>
                        <td>
                            <a href="{{ url('/admin/customers/' . $user->id . '/edit') }}"
                                style="display:inline-block !important;" class="btn btn-warning">
                                <i class="anticon anticon-edit"></i>
                            </a>
                            <form style="display:inline-block !important;"
                                action="{{ url('/admin/customers/' . $user->id) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <button type="submit" onclick="return confirm('Are You Sure To Delete This Account?')"
                                    class="btn btn-danger">
                                    <i class="anticon anticon-delete"></i>
                                </button>
                            </form>

                            <a href="{{ url('/admin/livechat/' . $user->id ) }}"
                                style="display:inline-block !important;" class="btn btn-info">
                                <i class="anticon anticon-customer-service"></i>
                            </a>
                        </td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <!-- User Create Modal -->
        <div class="modal fade" id="createModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">User Create</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="anticon anticon-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.customers.store')}}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="name">Name <span style="color:gold"> * </span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                        value="{{ old('name') }}" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="email">Email <span style="color:gold"> * </span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="password">Password <span style="color:gold"> * </span></label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="account_type">Account Type <span style="color:gold"> * </span> </label>
                                <select name="account_type" class="form-control" id="account_type">
                                    @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach

                                </select>

                            </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection