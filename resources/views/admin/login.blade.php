<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In Page | User</title>
</head>
<body style="display: flex; justify-content:center;">
<div style="margin-top: 100px">
    <div style="background:rgb(20, 220, 110); color: #ffffff;">
        <h1 style="padding: 10px">Log In User</h1>
    </div>
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>


