@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>EzHome</h1>
        <h5>Easy booking for your stay</h5>
        <div class="card">
            <div class="card-body">
                <form action="/booking/search" method="get">
                    <div class="text-left ml-1">
                        <a  data-toggle="collapse" href="#mycollapse"><h5 class="font-weight-bold text-dark">Search By Facilities <span>&#9660;</span></h5></a>
                    </div>
                    <div class="collapse" id="mycollapse">
                        <div class="row">
                            @foreach ($fac as $fac)
                            <div class="col-md-3  text-md-left">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="facilityArray[]" value="{{$fac->id}}" id="cbx{{$fac->id}}" class="form-check-input" style="width: 20px; height: 20px;">
                                    <label for="cbx{{$fac->id}}" class="form-check-label mr-5" style="font-size: 15px">{{$fac->facilityName}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="d-flex flex-row-reverse">
                                <button type="submit" class="btn btn-success">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @foreach ($homestays as $hs)
            <div class="card mt-3 text-left">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <img style="width: 100%; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);" src="/storage/myimage/{{$hs->image}}" alt="homestay image">
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <h3><a href="/homestay/{{$hs->id}}">{{$hs->name}}</a></h3>
                            <ul>
                                <li>{{$hs->address}}</li>
                                <li>{{$hs->type}}</li>
                                <li>{{$hs->numRoom}} Rooms</li>
                                <li>RM{{$hs->price}} / Night</li>
                            </ul>
                            <hr>
                            <h5 class="font-weight-bold">Facilities</h5>
                            <ul>
                            @foreach ($hs->facilities as $hsf)
                                <li>{{$hsf->facilityName}}</li>
                            @endforeach
                            </ul>
                            @if (Auth::guard('web')->check())
                                <div>
                                    <hr>
                                    <div>
                                        <a href="/booking/{{$hs->id}}/create" class="btn btn-primary float-right">Book Now</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

