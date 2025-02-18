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

        <div class="m-t-25">
            <table id="data-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Send</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counter = 1; @endphp

                    @foreach($phoneLists["register"] as $phone)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $phone->phone }}</td>
                            <td>Register</td>
                            <td>
                                <a href="/admin/sms/send/{{$phone->phone}}" class="btn btn-primary" >Send SMS</a>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($phoneLists["orders"] as $phone)
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td>{{ $phone->phone }}</td>
                            <td>Order</td>
                            <td> <a href="/admin/sms/send/{{$phone->phone}}" class="btn btn-primary" >Send SMS</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
