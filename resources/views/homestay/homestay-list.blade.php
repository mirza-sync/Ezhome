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
                                <li>{{$hs->type}}</li>
                                <li>{{$hs->numRoom}} Rooms</li>
                                <li>RM{{$hs->price}} / Night</li>
                            </ul>
                            <div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <form action="/homestay/{{$hs->id}}" method="post">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    <a href="/homestay/{{$hs->id}}" class="btn btn-success">View</a>
                                    <a href="/homestay/{{$hs->id}}/edit" class="btn btn-primary">Edit</a>
                                </div>
                            </div>
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