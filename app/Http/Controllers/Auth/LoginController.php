<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import the Hash facade
use App\User; // Import the User model

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Check if the user is already logged in
        if (Auth::check()) {
            return redirect()->intended('movies'); // Redirect to the intended page if already logged in
        }

        // Validate the request...
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to login the user using username
        $user = User::where('username', $request->username)->first();

        // Check if user exists and verify the password
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            session(['logged_in' => true]);
            session()->flash('status', 'Login successful'); // Flash message for confirmation
            return redirect()->intended('movies');
        }

        \Log::info('User logged in check: ' . Auth::check());

        // If login fails, redirect back with an error
        return back()->withErrors([
            'username' => 'Wrong Username / Password',
        ]);
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
