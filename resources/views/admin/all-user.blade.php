@extends('layouts.app')

@section('content')
    <h3>User List</h3>
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
            @if (count($users)>0)
                @foreach ($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td>
                        <form action="/admin/{{$user->id}}" method="get"></form>
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="type" value="user">
                        <input class="btn btn-danger" type="submit" value="Delete" />
                    </td>
                </tr>
                @endforeach
            @else
                No users found!
            @endif
        </tbody>
    </table>
@endsection