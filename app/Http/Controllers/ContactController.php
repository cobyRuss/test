<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $errors = [];

        $name = trim((string) $request->input('name'));
        $email = trim((string) $request->input('email'));
        $message = trim((string) $request->input('message'));

        if ($name === '') {
            $errors['name'] = 'Name is required';
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        if ($message === '') {
            $errors['message'] = 'Message is required';
        }

        if (! empty($errors)) {
            session()->flash('contact_errors', $errors);

            return redirect()->route('home');
        }

        ContactMessage::query()->create([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);

        session()->flash('contact_success', "Thank you for your message! We'll get back to you soon.");

        return redirect()->route('home');
    }
}
