@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">Edit {{ $facility->facilityName }}</div>
        <div class="card-body mx-5">
            <form action="/facility/{{$facility->id}}" method="post">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group row mb-4">
                    <label for="facilityName" class="col-md-4 col-form-label font-weight-bold">{{ __('Facility Name') }}</label>
                    <div class="col-md-8">
                        <input id="facilityName" type="text" class="form-control @error('facilityName') is-invalid @enderror" name="facilityName" value="{{ $facility->facilityName }}" required autocomplete="facilityName" autofocus>
                    </div>
                </div>
                <div class="form-group row my-1">
                    <div class="ml-3">
                        <button type="submit" class="btn btn-success float-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection