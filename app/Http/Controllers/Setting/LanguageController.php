<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    // List all language files
    public function index()
    {
        $languages = collect(File::files(resource_path('lang')))
            ->filter(fn ($file) => $file->getExtension() === 'json')
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->values();

        return view('setting.language.index', compact('languages'));
    }

    // Show a single language's translations
    public function show($lang)
    {
        $path = resource_path("lang/{$lang}.json");

        if (! File::exists($path)) {
            return redirect()->route('languages.list')
                ->with('error', 'Language file not found.');
        }

        $json = json_decode(File::get($path), true);

        return view('setting.language.show', compact('lang', 'json'));
    }

    // Switch app locale
    public function switch($lang)
    {
        Session::put('locale', $lang);

        return back();
    }

    // Show form to create a new language
    public function create()
    {
        return view('setting.language.create');
    }

    // Store a new language file
    public function store(Request $request)
    {
        $request->validate([
            'locale' => ['required', 'alpha'],
        ]);

        $locale = strtolower(trim($request->input('locale')));

        // Gather dynamic keys and values
        $keys = $request->input('dynamic_keys', []);
        $values = $request->input('dynamic_values', []);

        $newTranslations = [];
        foreach ($keys as $index => $key) {
            $key = trim($key);
            $value = trim($values[$index] ?? '');
            if (! empty($key) && $value !== '') {
                $newTranslations[$key] = $value;
            }
        }

        if (empty($newTranslations)) {
            return back()->with('error', 'No valid translations provided.');
        }

        $langDir = resource_path('lang');
        $langFile = $langDir."/{$locale}.json";

        if (! File::exists($langDir)) {
            File::makeDirectory($langDir, 0755, true);
        }

        $existingTranslations = [];
        if (File::exists($langFile)) {
            $existingTranslations = json_decode(File::get($langFile), true) ?? [];
        }

        $merged = array_merge($existingTranslations, $newTranslations);
        File::put($langFile, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('languages.list')->with('success', 'Language saved/updated successfully.');
    }

    // Edit language form
    public function edit($lang)
    {
        $path = resource_path("lang/{$lang}.json");

        if (! File::exists($path)) {
            return redirect()->route('languages.list')->with('error', 'Language file not found.');
        }

        $translations = json_decode(File::get($path), true);

        return view('setting.language.edit', compact('lang', 'translations'));
    }

    // Update language file
    public function update(Request $request, $lang)
    {
        $request->validate([
            'dynamic_keys' => 'required|array',
            'dynamic_values' => 'required|array',
        ]);

        $keys = $request->input('dynamic_keys', []);
        $values = $request->input('dynamic_values', []);

        $updatedTranslations = [];
        foreach ($keys as $index => $key) {
            $key = trim($key);
            $value = trim($values[$index] ?? '');
            if (! empty($key) && $value !== '') {
                $updatedTranslations[$key] = $value;
            }
        }

        if (empty($updatedTranslations)) {
            return back()->with('error', 'No valid translations provided.');
        }

        $langFile = resource_path("lang/{$lang}.json");
        File::put($langFile, json_encode($updatedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('languages.list')->with('success', 'Language updated successfully.');
    }

   public function switchLanguage($lang)
{
    $availableLocales = ['en', 'am', 'or'];
    if (in_array($lang, $availableLocales)) {
        Session::put('locale', $lang);
    }
    return redirect()->back();
}
}
