<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
class GuestController extends Controller
{
    //
    public function loginPage(){
        return view('auth.login');
    }

    public function registerPage(){
        return view('auth.register');
    }

    public function login(Request $request){
        $request = Request::create('/api/users/login', 'POST',$request->all());
          $result = Route::dispatch($request);
          $response = json_decode($result->getContent());
          print_r($result->getContent());
          return;
        // $response = Http::post(config('app.url') . '/api/users/login', $request->all());
        return redirect()->route('loginPage')->with('status', 'User logged in successfully.');
    }
}
