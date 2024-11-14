@extends('admin.master')

@section('title', 'Edit Page')

@section('content')
<div class="buttons" style="width: 100vw; height: auto; text-align:center; margin-top: 20px">
    <button id="logout" style="background: #ff4848;color: white;padding: 5px 25px;font-size: 18px;cursor: pointer;border: 1px solid #2ce0f8;"
    >Log Out</button>

</div>



<div style="margin:150px auto -10px auto ;background:#157e8b;color:aliceblue; width:620px;">
    <h1 style="padding: 10px">User Edit</h1>
</div>
<div class="content" style="margin:auto;width:620px;height:auto;display:flex;justify-content:space-evenly;align-items:center;gap:20px;border:2px solid #157e8b;">
    <div class="user-details">
        <h2 style="">Hi, <span id="name" style="color:brown;font-weight:600"></span></h2>
        <p style="color: brown; font-weight:600">Email: <span id="email"></span></p>
        <button id="verify" data-id="" style="border:0;margin-bottom:10px;"></button>
    </div>
    <div class="user-form">
        <form id="update_form" action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" value="{{$user->id}}" name="id" id="id">
            <label for="name" style="color:#18a5b8;font-weight:600">Your Name: </label>
            <input style="border:1px solid #18a5b8;padding:2px;color:#18a5b8;outline:#18a5b8"
                type="text" name="first_name" id="first_name" placeholder="Enter name"value="{{$user->first_name}}"><br><br>
            <label for="email" style="color:#18a5b8;font-weight:600">Your Email: </label>
            <input style="border:1px solid #18a5b8;padding:2px;color:#18a5b8;outline:#18a5b8"
                type="email" name="email" id="email" placeholder="Enter email" value="{{$user->email}}"><br><br>
            <button type="submit" style="padding:5px 15px;font-size:16px;background:#157e8b;color:aliceblue;border:0;cursor:pointer"
            >Update Profile</button>
        </form>
    </div>
</div>
@endsection
