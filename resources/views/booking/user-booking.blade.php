@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">My Booking</div>
        <div class="card-body">
            <div class="row d-flex align-items-center">
                <div class="col-md-4 col-sm-4">
                    <img class="shadow-sm" style="width: 100%" src="/storage/myimage/{{$booking->homestay->image}}" alt="homestay image">
                </div>
                <div class="col-md-8 col-sm-8">
                    <h3><a href="/homestay/{{$booking->homestay->id}}">{{$booking->homestay->name}}</a></h3>
                    <ul>
                        <li>{{$booking->homestay->address}}</li>
                        <li>{{$booking->homestay->type}}</li>
                        <li>{{$booking->homestay->numRoom}} Rooms</li>
                        <li>RM{{$booking->homestay->price}} / Night</li>
                    </ul>
                    <div>
                        <hr>
                        <div class="ml-4">
                            <h6>Landlord : {{$booking->homestay->landlord->name}}</h6>
                            <h6>Phone : {{$booking->homestay->landlord->phone}}</h6>
                            <hr>
                            <h6>Check-In Date : {{\Carbon\Carbon::parse($booking->checkInDate)->format('d M Y')}}</h6>
                            <h6>Check-Out Date : {{\Carbon\Carbon::parse($booking->checkOutDate)->format('d M Y')}}</h6>
                            <hr>
                            <h6>Number of Days : {{$days}}</h6>
                            <strong>Total Amount : RM{{$total}}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection