@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">User Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <hr>
                    <a href="/homestay" class="btn btn-primary">Homestay List</a>
                    <a href="/booking" class="btn btn-primary">My Bookings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
