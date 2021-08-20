@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">Edit Homestay</div>
        <div class="card-body">
            <form action="/homestay/{{$hs->id}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Homestay Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$hs->name}}" required autocomplete="name" autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                    <div class="col-md-6">
                        <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{$hs->address}}" required autocomplete="address" autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="image" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>

                    <div class="col-md-6">
                        <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ $hs->image }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                    <div class="col-md-6">
                        <select class="form-control" name="type" id="type">
                            <option value="{{$hs->type}}" selected>{{$hs->type}}</option>
                            <option value="Condominium">Condominium</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Bungalow">Bungalow</option>
                            <option value="Terrace">Terrace</option>
                            <option value="Semi-D">Semi-D</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="room" class="col-md-4 col-form-label text-md-right">{{ __('Number of Room') }}</label>

                    <div class="col-md-6">
                        <input id="room" type="number" class="form-control @error('room') is-invalid @enderror" name="room" value="{{ $hs->numRoom }}" required autocomplete="room" autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price per Night') }}</label>

                    <div class="col-md-6">
                        <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $hs->price }}" required autocomplete="price" autofocus>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-success float-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection