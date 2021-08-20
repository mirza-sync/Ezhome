@extends('layouts.app')

@section('content')
    <h3 class="d-inline-block">Facility List</h3>
    <a href="/facility/create" class="btn btn-success float-right mb-3">Add Facility</a>
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
@endsection