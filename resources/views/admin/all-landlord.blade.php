@extends('layouts.app')

@section('content')
<h3>Landlord List</h3>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($landlords)>0)
                @foreach ($landlords as $landlord)
                <tr>
                    <td>{{$landlord->id}}</td>
                    <td>{{$landlord->name}}</td>
                    <td>{{$landlord->email}}</td>
                    <td>{{$landlord->phone}}</td>
                    <td>
                        <form action="/admin/{{$landlord->id}}" method="get"></form>
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="type" value="landlord">
                        <input class="btn btn-danger" type="submit" value="Delete" />
                    </td>
                </tr>
                @endforeach
            @else
                No landlords found!
            @endif
        </tbody>
    </table>
    <hr>
    <div class="mt-4">
        <a href="/landlord/register" class="btn btn-success float-right">Register New Landlord</a>
    </div>
@endsection