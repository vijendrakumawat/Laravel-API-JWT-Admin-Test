@extends('admin.master')

@section('title', 'Dashboard Page')

@section('content')

<a href="{{ route('admin.logout') }}">Logout</a>
<h1>Welcome, Admin</h1>
<a href="{{ route('admin.users') }}">Manage Users</a>

@endsection
