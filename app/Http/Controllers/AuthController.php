<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form with role selection
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
            'role' => ['required', 'in:doctor,admin,employee'],
        ]);

        // Add role to credentials for authentication
        if (Auth::attempt([
            'name' => $credentials['name'],
            'password' => $credentials['password'],
            'role' => $credentials['role']
        ])) {
            $request->session()->regenerate();

            // Redirect based on role
            switch (Auth::user()->role) {
                case 'doctor':
                    return redirect()->route('doctor.dashboard');
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'employee':
                    return redirect()->route('employee.dashboard');
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the registration form (admin only)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request (admin only)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:doctor,admin,employee'],
            'center_name' => ['required_if:role,doctor', 'nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            // 'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'center_name' => $request->center_name,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }
}
