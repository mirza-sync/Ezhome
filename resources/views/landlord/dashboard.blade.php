@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Landlord Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as landlord!
                    <hr>
                    <a href="/homestay/create" class="btn btn-primary">New Homestay</a>
                    <a href="/booking" class="btn btn-primary">Booking List</a>
                    <a href="/homestay" class="btn btn-primary">My Homestay</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
