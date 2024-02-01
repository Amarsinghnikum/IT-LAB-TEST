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
            <h1 class="text-center">Create User</h1>
            <form id="createUserForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile No.:</label>
                    <input type="tel" name="mobile" id="mobile" class="form-control" required pattern="[0-9]{10}" minlength="10" maxlength="10">
                </div>

                <div class="form-group">
                    <label for="profile_pic">Profile Pic:</label>
                    <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/png, image/jpeg, image/jpg">
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="8">
                </div>

                <button type="button" class="btn btn-primary" onclick="submitForm()">Save User</button>
            </form>
        </div>
    </div>
</div>
@endsection
