<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['error' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function listUsers(Request $request)
    {
        $users = User::paginate(2);
        if ($request->ajax()) {
            return view('admin.partials.users_table', compact('users'))->render();
        }
        return view('admin.users', compact('users'));
    }
    public function editUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        return view('admin.edit', compact('user'));

    }

    public function update(Request $request, $id)
    {
        $post = User::findOrFail($id);
        // Validate the input data
        $validated = $request->validate([

            'status' => 'in:Active,Inactive',
        ]);


        $post->update($request->all());

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
