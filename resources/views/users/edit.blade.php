@extends('layouts.app')

@section('content')

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center">Edit User</h1>
            <form id="updateUserForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $user->id }}">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}">
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile No.:</label>
                    <input type="tel" name="mobile" id="mobile" class="form-control" value="{{ $user->mobile }}"
                        pattern="[0-9]{10}" minlength="10" maxlength="10">
                </div>

                <div class="form-group">
                    <label for="profile_pic">Profile Pic:</label>
                    <input type="file" name="profile_pic" id="profile_pic" class="form-control"
                        accept="image/png, image/jpeg, image/jpg">
                    @if ($user->profile_pic)
                    <img src="{{ asset('profile_pics/' . $user->profile_pic) }}" alt="Current Profile Pic" width="100">
                    @else
                    <p>No current profile picture</p>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="text" name="password" id="password" class="form-control" value="{{ $user->password }}">
                </div>

                <button type="button" class="btn btn-primary" onclick="submitUpdateForm()">Update User</button>
            </form>
        </div>
    </div>
</div>
@endsection