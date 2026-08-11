<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function forgot(Request $request)
    {
        $error = '';
        $success = '';
        $step = 1;
        $email = '';

        if ($request->isMethod('POST') && $request->has('send_code')) {
            $email = trim((string) $request->input('email'));
            $customer = Customer::query()->where('email', $email)->first();

            if ($customer) {
                $code = sprintf('%06d', mt_rand(1, 999999));
                $expires = now()->addMinutes(15);

                $customer->update([
                    'reset_code' => $code,
                    'reset_code_expires' => $expires,
                    'reset_code_attempts' => 0,
                ]);

                session(['reset_email' => $email]);

                try {
                    Mail::to($customer->email)->send(new OtpMail($customer->full_name, $code, $expires));
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('OTP email failed: '.$e->getMessage());
                }

                $step = 2;
                $success = 'A 6-digit verification code has been sent to your email.';
            } else {
                $error = 'Email address not found in our records.';
            }
        }

        if ($request->isMethod('POST') && $request->has('verify_code')) {
            $email = session('reset_email');
            $code = trim((string) $request->input('code'));

            $customer = Customer::query()
                ->where('email', $email)
                ->where('reset_code', $code)
                ->where('reset_code_expires', '>', now())
                ->first();

            if ($customer) {
                session(['reset_customer_id' => $customer->id]);
                session(['reset_customer_name' => $customer->full_name]);
                $step = 3;
                $success = 'Code verified! Please enter your new password.';
            } else {
                Customer::query()->where('email', $email)->increment('reset_code_attempts');
                $error = 'Invalid or expired verification code. Please try again.';
            }
        }

        if ($request->isMethod('POST') && $request->has('reset_password')) {
            $password = $request->input('password');
            $confirmPassword = $request->input('confirm_password');
            $customerId = (int) session('reset_customer_id', 0);

            if (strlen((string) $password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match.';
            } else {
                Customer::query()->where('id', $customerId)->update([
                    'password_hash' => Hash::make($password),
                    'reset_code' => null,
                    'reset_code_expires' => null,
                ]);

                session()->forget(['reset_email', 'reset_code', 'reset_customer_id', 'reset_customer_name']);

                $success = 'Password reset successfully! You can now login with your new password.';
                $step = 4;
            }
        }

        $resend = $step === 2;
        $resetName = session('reset_customer_name');

        return view('auth.forgot', compact('error', 'success', 'step', 'email', 'resetName', 'resend'));
    }

    public function showReset(Request $request)
    {
        $token = $request->query('token', '');
        $customer = null;
        $error = '';
        $success = '';

        if (empty($token)) {
            return redirect()->route('forgot');
        }

        $customer = Customer::query()
            ->where('reset_token', $token)
            ->where('reset_expires', '>', now())
            ->first();

        if (! $customer) {
            $error = 'Invalid or expired reset link. Please request a new one.';
        }

        return view('auth.reset', compact('customer', 'error', 'success', 'token'));
    }

    public function reset(Request $request)
    {
        $token = $request->query('token', '');
        $customer = Customer::query()
            ->where('reset_token', $token)
            ->where('reset_expires', '>', now())
            ->first();

        $error = '';
        $success = '';

        if (! $customer) {
            $error = 'Invalid or expired reset link. Please request a new one.';

            return view('auth.reset', compact('customer', 'error', 'success', 'token'));
        }

        $password = $request->input('password');
        $confirmPassword = $request->input('confirm_password');

        if (strlen((string) $password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {
            $customer->update([
                'password_hash' => Hash::make($password),
                'reset_token' => null,
                'reset_expires' => null,
            ]);

            $success = 'Password reset successfully! You can now login with your new password.';
        }

        return view('auth.reset', compact('customer', 'error', 'success', 'token'));
    }
}
