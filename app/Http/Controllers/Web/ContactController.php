<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // عدّل الإيميل هون لو بدك ترسل لعنوان مختلف عن الموجود بالـ footer
        Mail::to('info@hotel.com')->send(new ContactMessageMail(
            $data['name'],
            $data['email'],
            $data['message'],
        ));

        return redirect()
            ->route('contact.show')
            ->with('success', 'Your message has been sent. We will get back to you soon.');
    }
}
