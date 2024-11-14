@foreach($users as $user)
<tr>
    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->status }}</td>
    <td>
        <form action="{{ route('admin.users.edit', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit">Edit</button>
        </form>
        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
@endforeach
