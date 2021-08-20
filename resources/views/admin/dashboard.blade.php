@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as Admin!
                    <hr>
                    <a href="/admin/users" class="btn btn-primary">All Users</a>
                    <a href="/admin/landlords" class="btn btn-primary">All Landlords</a>
                    <a href="/admin/facilities" class="btn btn-primary">All Facilities</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
