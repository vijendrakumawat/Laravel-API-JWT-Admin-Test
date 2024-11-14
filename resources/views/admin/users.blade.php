@extends('admin.master')

@section('title', 'Users Page')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody id="user-table">
        @include('admin.partials.users_table', ['users' => $users])
    </tbody>
</table>

<!-- Laravel pagination links -->
<div id="pagination-links">
    {{ $users->links() }}
</div>
@endsection

<!-- AJAX script for pagination -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        let page = $(this).attr('href').split('page=')[1];
        fetchUsers(page);
    });

    function fetchUsers(page) {
        $.ajax({
            url: `/admin/users?page=${page}`,
            success: function(data) {
                // Update table and pagination links with the new data
                $('#user-table').html($(data).find('#user-table').html());
                $('#pagination-links').html($(data).find('#pagination-links').html());
            },
            error: function() {
                alert("Could not load users. Please try again.");
            }
        });
    }
</script>
