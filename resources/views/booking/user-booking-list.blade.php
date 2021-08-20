@extends('layouts.app')

@section('content')
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Homestay Name</th>
                <th>Booking Date</th>
                <th>Check-In Date</th>
                <th>Check-Out Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($bookings)>0)
                @foreach ($bookings as $booking)
                <tr>
                    <td>{{$booking->id}}</td>
                    <td>{{$booking->homestay->name}}</td>
                    <td>{{\Carbon\Carbon::parse($booking->bookingDate)->format('d M Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($booking->checkInDate)->format('d M Y')}}</td>
                    <td>{{\Carbon\Carbon::parse($booking->checkOutDate)->format('d M Y')}}</td>
                    <td>
                        <form action="booking/{{$booking->id}}" method="post">
                            @csrf
                            <a href="/booking/{{$booking->id}}" class="btn btn-primary">View</a>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @else
                No bookings found!
            @endif
        </tbody>
    </table>
@endsection