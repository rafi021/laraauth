<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterStoreRequest;
use Illuminate\Support\Facades\Auth;

class CustomRegisterController extends Controller
{
    public function registerFormShow()
    {
        return view('custom-auth.register');
    }

    public function registerUser(RegisterStoreRequest $request)
    {

        // User create
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // make a credentials array
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // login attempt if success then redirect home
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        // return error message
        return back()->withErrors([
            'email' => 'Wrong Credentials found!'
        ])->onlyInput('email');


    }


    public function loginFormShow()
    {
        return view('custom-auth.login');
    }

    public function loginUser(LoginUserRequest $request)
    {
        // dd($request->all());
       // make a credentials array
       $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        ];

        // login attempt if success then redirect home
        if(Auth::attempt($credentials, $request->filled('remember'))){
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        // return error message
        return back()->withErrors([
            'email' => 'Wrong Credentials found!'
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
