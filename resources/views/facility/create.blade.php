@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">Create Facility</div>
        <div class="card-body mx-5">
            <form action="/facility" method="post">
                @csrf
                <div class="form-group row mb-4">
                    <label for="facilityName" class="col-md-4 col-form-label font-weight-bold">{{ __('Facility Name') }}</label>
                    <div class="col-md-8">
                        <input id="facilityName" type="text" class="form-control @error('facilityName') is-invalid @enderror" name="facilityName" value="{{ old('facilityName') }}" required autocomplete="facilityName" autofocus>
                    </div>
                </div>
                <div class="form-group row my-1">
                    <div class="ml-3">
                        <button type="submit" class="btn btn-success float-right">Add Facility</button>
                    </div>
                </div>
            </form>
            <hr class="mb-5">
            <h3>Facility List</h3>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($facilitys)>0)
                        @foreach ($facilitys as $facility)
                        <tr>
                            <td>{{$facility->id}}</td>
                            <td>{{$facility->facilityName}}</td>
                            <td>
                                <a href="/facility/{{$facility->id}}/edit" class="btn btn-primary">Edit</a>
                                <form action="/facility/{{$facility->id}}" method="post" class="d-inline-block">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        No facilities found!
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection