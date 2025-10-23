<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\TranslateRequest;
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

    public function translate(TranslateRequest $request)
    {
        $data = $request->validated();

        $text   = $data['text'];
        $source = strtoupper($data['source']); // 'PL', 'EN'
        $target = strtoupper($data['target']);

        $apiKey = config('services.deepl.key');

        // Brak klucza — kopia (fallback)
        if (!$apiKey) {
            return response()->json([
                'text'     => $text,
                'copied'   => true,
                'provider' => 'none',
            ]);
        }

        // DeepL endpoint (free albo pro)
        $base = config('services.deepl.base', 'https://api-free.deepl.com');
        $resp = Http::asForm()->withHeaders([
            'Authorization' => "DeepL-Auth-Key {$apiKey}",
        ])->post("{$base}/v2/translate", [
            'text'        => $text,
            'source_lang' => $source,
            'target_lang' => $target,
            // 'preserve_formatting' => 1, // opcjonalnie
        ]);

        if (!$resp->ok()) {
            return response()->json([
                'text'     => $text,
                'copied'   => true,
                'provider' => 'deepl',
                'error'    => $resp->json(),
            ], 200);
        }

        $out = $resp->json();
        $translated = $out['translations'][0]['text'] ?? $text;

        return response()->json([
            'text'     => $translated,
            'copied'   => false,
            'provider' => 'deepl',
        ]);
    }
}
