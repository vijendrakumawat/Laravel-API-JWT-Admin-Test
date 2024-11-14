@foreach($users as $key =>$user)
<tr>
    <td>{{$key+1}}</td>
    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->status }}</td>
    <td>
        <td><a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Edit</a>

        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </td>
</tr>
@endforeach
