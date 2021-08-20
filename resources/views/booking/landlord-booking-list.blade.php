@extends('layouts.app')

@section('content')
    <h1>My Homestays</h1>
    @if (count($homestays)>0)
        @foreach ($homestays as $hs)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-4 col-sm-4">
                            <img style="width: 100%" src="/storage/myimage/{{$hs->image}}" alt="homestay image">
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <h3><a href="/homestay/{{$hs->id}}">{{$hs->name}}</a></h3>
                            <ul>
                                <li>{{$hs->address}}</li>
                                <li>RM{{$hs->price}} / Night</li>
                            </ul>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Booking Date</th>
                                        <th>Check-In Date</th>
                                        <th>Check-Out Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hs->booking as $hsbk)
                                        <tr>
                                            <td>{{$hsbk->id}}</td>
                                            <td>{{\Carbon\Carbon::parse($hsbk->bookingDate)->format('d M Y')}}</td>
                                            <td>{{\Carbon\Carbon::parse($hsbk->checkInDate)->format('d M Y')}}</td>
                                            <td>{{\Carbon\Carbon::parse($hsbk->checkOutDate)->format('d M Y')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        No homestay found!
    @endif
    <a href="/homestay/create" class="btn btn-success float-right mb-5">Add Homestay</a>
@endsection