@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>User List</h1>
    <div>
        <a href="{{ route('users.create') }}" class="btn btn-success mr-2">Create User</a>
        <a href="{{ route('users.export') }}" class="btn btn-primary">Export Users</a>
    </div>
</div>

<div>
    <input type="text" id="searchInput" placeholder="Search users">
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table" id="userTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Profile Pic</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="userTableBody">
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->mobile }}</td>
            <td>
                @if($user->profile_pic)
                <img src="{{ asset('/profile_pics/' . $user->profile_pic) }}" alt="Profile Pic" width="50">
                @else
                No Image
                @endif
            </td>
            <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <button type="button" class="btn btn-sm btn-danger"
                    onclick="deleteUser({{ $user->id }})">Delete</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No users found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection