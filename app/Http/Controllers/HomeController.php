<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactFormRequest;

class HomeController extends Controller
{
    public function about()
    {
        return view('layout.about');
    }

    public function contact()
    {
        return view('layout.contact');
    }

    public function sendContact(ContactFormRequest $request)
    {
        $data = $request->validated();

        Mail::to(config('mail.from.address'))->send(new ContactMail($data));

        return back()->with('success', __('app.contact.thanks'));
    }

    public function changeLanguage(string $language)
    {
        if(Auth::check()) Auth::user()->setLanguage($language);
        session()->put('language', $language);

        return back();
    }
}
