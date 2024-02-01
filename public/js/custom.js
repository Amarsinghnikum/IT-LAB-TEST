$(document).ready(function () {
    $("#createUserForm").click(function () {
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
            success: function (response) {
                console.log(response);
                window.location.href = '{{ route("users.index") }}';
            },
            error: function (error) {
                console.log(error);
                alert('Error creating user. Please check the console for details.');
            }
        });
    });

    $("#updateUserForm").click(function () {
        var userId = $(this).data("user-id");
        var formData = new FormData($('#updateUserForm')[0]);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: userId ? '{{ route("users.update", $user->id) }}' : '{{ route("users.update", "null") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                window.location.href = '{{ route("users.index") }}';
            },
            error: function(error) {
                console.log(error);
                alert('Error updating user. Please check the console for details.');
            }
        });
    });

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/users/' + userId,
                method: 'DELETE',
                success: function(response) {
                    console.log(response);
                    alert('User deleted successfully!');
                    $("#userTable tbody").find('tr[data-id="' + userId + '"]').remove();
                },
                error: function(error) {
                    console.log(error);
                    alert('Error deleting user. Please check the console for details.');
                }
            });
        }
    }
});