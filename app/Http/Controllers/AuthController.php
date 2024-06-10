<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user);

        $notes = auth()->user()->notes;
        $users = User::all();

        return view('notes.index', [
            'notes' => $notes,
            'users' => $users
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        auth()->attempt($credentials);

        $notes = auth()->user()->notes;
        $users = User::all();

        return view('notes.index', [
            'notes' => $notes,
            'users' => $users
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return redirect('/register');
    }

}
