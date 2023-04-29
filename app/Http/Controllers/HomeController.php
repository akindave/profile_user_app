<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function indexPage(){

        $user = Auth::user();
        if(!$user){
            return redirect()->to('/login');
        }
        return view('home',['user'=>$user]);
    }
}
