<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IT LABS TEST</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!-- <b>IT LABS TEST</b> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                </li>
                <li class="nav-item {{ request()->is('audio-length') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('audio-length') }}">Audio Length</a>
                </li>
                <li class="nav-item {{ request()->is('calculate-distance') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('calculate-distance') }}">Distance Cal.</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session('status'))
    <script>
    swal("{{ session('status') }}");
    </script>
    @endif

    <script>
    function submitForm() {
        var formData = new FormData($('#createUserForm')[0]);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route("users.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                swal({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    window.location.replace('{{ route("users.index") }}');
                });
            },
            error: function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON.errors) {
                    $('.alert-danger').remove();
                    $.each(error.responseJSON.errors, function(field, messages) {
                        var inputField = $('#' + field);
                        inputField.after('<div class="alert alert-danger">' + messages.join('<br>') + '</div>');
                    });
                } else {
                    alert('Error creating user. Please check the console for details.');
                }
            }
        });
    }
    
    function submitUpdateForm() {
        var userId = {{ isset($user) ? $user->id : 'null' }};
        var formData = new FormData($('#updateUserForm')[0]);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: userId ? '{{ isset($user) ? route("users.update", [$user->id]) : "" }}' : '{{ route("users.update", ["null"]) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                swal({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    window.location.replace('{{ route("users.index") }}');
                });
            },
            error: function(error) {
                console.log(error);
                if (error.responseJSON && error.responseJSON.errors) {
                    $('.alert-danger').remove();
                    $.each(error.responseJSON.errors, function(field, messages) {
                        var inputField = $('#' + field);
                        inputField.after('<div class="alert alert-danger">' + messages.join('<br>') + '</div>');
                    });
                } else {
                    alert('Error updating user. Please check the console for details.');
                }
            }
        });
    }
    
    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
        axios.delete('/users/' + userId)
            .then(function(response) {
                console.log(response);
                swal({
                    title: 'Success',
                    text: response.data.message,
                    icon: 'success',
                    button: 'OK',
                }).then(() => {
                    $("#userTable tbody tr[data-id='" + userId + "']").remove();
                    $("#userTable").load(location.href + " #userTable");
                });
            })
            .catch(function(error) {
                console.log(error);

                swal({
                    title: 'Error',
                    text: 'Error deleting user. Please check the console for details.',
                    icon: 'error',
                    button: 'OK',
                });
            });
        }
    }

    $(document).ready(function () {
        $('#searchInput').on('keyup', function () {
            clearTimeout($(this).data('timer'));
            $(this).data('timer', setTimeout(searchUsers, 300));
        });
    });

    function searchUsers() {
        var query = $('#searchInput').val();

        $.ajax({
            url: '/users/search',
            method: 'GET',
            data: { query: query },
            success: function(response) {
                updateTable(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function updateTable(users) {
        var tableBody = $('#userTableBody');
        tableBody.empty();

        $.each(users, function(index, user) {
            var editButton = '<a href="/users/' + user.id + '/edit" class="btn btn-sm btn-primary">Edit</a>';
            var deleteButton = '<button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(' + user.id + ')">Delete</button>';
            var profilePic = user.profile_pic ? '<img src="/profile_pics/' + user.profile_pic + '" alt="Profile Pic" width="50">' : 'No Image';

            var row = '<tr><td>' + user.id + '</td><td>' + user.name + '</td><td>' + user.email + '</td><td>' 
                + user.mobile + '</td><td>' + profilePic + '</td><td>' + editButton + ' ' + deleteButton + '</td></tr>';
            tableBody.append(row);
        });
    }
    </script>

</body>
</html>