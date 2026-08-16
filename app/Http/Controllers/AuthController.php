<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('account');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $customer = Auth::guard('web')->user();

            session([
                'customer_name' => $customer->full_name,
                'customer_email' => $customer->email,
            ]);

            $request->session()->regenerate();

            $redirect = $request->input('redirect');

            if ($redirect && ! str_starts_with($redirect, 'http')) {
                return redirect($redirect);
            }

            return redirect()->route('account');
        }

        return back()->with('error', 'Invalid email or password');
    }

    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('account');
        }

        return view('auth.register', ['municipalities' => array_keys(config('deliveryfees'))]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'same:password'],
        ], [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Valid email is required',
            'email.unique' => 'Email already registered',
            'password.min' => 'Password must be at least 6 characters',
            'confirm_password.same' => 'Passwords do not match',
        ]);

        $customer = Customer::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'full_name' => trim($data['first_name'].' '.$data['last_name']),
            'email' => $data['email'],
            'password_hash' => bcrypt($data['password']),
        ]);

        Auth::guard('web')->login($customer);

        session([
            'customer_name' => $customer->full_name,
            'customer_email' => $customer->email,
        ]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
