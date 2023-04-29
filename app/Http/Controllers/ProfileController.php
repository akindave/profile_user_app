<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function show()
    {
        $response = Http::withToken(auth()->user()->api_token)->get(config('app.url') . '/api/profile');
        return view('profile.show', ['user' => $response->json()]);
    }

    public function edit()
    {
        $response = Http::withToken(auth()->user()->api_token)->get(config('app.url') . '/api/profile');
        return view('profile.edit', ['user' => $response->json()]);
    }

    public function update(Request $request)
    {
        $response = Http::withToken(auth()->user()->api_token)->put(config('app.url') . '/api/profile', $request->all());
        return redirect()->route('profile.show')->with('status', 'Profile updated successfully.');
    }

    public function destroy()
    {
        $response = Http::withToken(auth()->user()->api_token)->delete(config('app.url') . '/api/profile');
        return redirect()->route('home')->with('status', 'Profile deleted successfully.');
    }
}
