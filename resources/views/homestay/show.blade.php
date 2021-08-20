@extends('layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <img class="shadow w-100" src="/storage/myimage/{{$hs->image}}" alt="homestay image">
                </div>
                <div class="col-md-8 col-sm-8">
                    <h3>{{$hs->name}}</h3>
                    <ul>
                        <li>{{$hs->address}}</li>
                        <li>{{$hs->type}}</li>
                        <li>{{$hs->numRoom}} Rooms</li>
                        <li>RM{{$hs->price}} / Night</li>
                    </ul>
                    <hr>
                    <div>
                        <div class="d-flex justify-content-between">
                            <form action="/homestay/{{$hs->id}}" method="post">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <a href="/homestay/{{$hs->id}}/edit" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                    <h4 class="mt-5">Facility</h4>
                    @if (count($hs->facilities) > 0)
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 10%"></th>
                                    <th>Facility Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hs->facilities as $hsf)
                                <tr>
                                    <td><a href="/removeFac/{{$hs->id}}/{{$hsf->id}}" class="btn btn-danger btn-sm">X</a></td>
                                    <td>{{$hsf->facilityName}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        No facilities assigned...
                    @endif
                    <hr class="mt-5">
                    <form action="/assignFac" method="post">
                        @csrf
                        <input type="hidden" name="hs_id" value="{{$hs->id}}">
                        @foreach ($fac as $fac)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" name="facilityArray[]" value="{{$fac->id}}" id="cbx{{$fac->id}}" class="form-check-input" style="width: 20px; height: 20px;">
                            <label for="cbx{{$fac->id}}" class="form-check-label mr-5" style="font-size: 15px">{{$fac->facilityName}}</label>
                        </div>
                        @endforeach
                        <div class="float-right mt-3">
                            <button type="submit" class="btn btn-success mt-3">Assign Facility</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection