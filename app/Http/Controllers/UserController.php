<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Show register/Create Form
    public function create() {
        
        return view('users.register');
    }

    //Create a user
    public function store(Request $request) {
        $formFields = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);
        
        $formFields['password'] = bcrypt($formFields['password']);

        $user = User::create($formFields);

        auth()->login($user);
        
        return redirect()->route('listings.index')->with('message', 'User created and logged in');
    }

    //Log user out
    public function logout(Request $request) {
        
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('listings.index')->with('message', 'You have been logged out!');
    }

    //Show login form
    public function login() {
        return view('users.login');
    }

    //Authenticated a user 
    public function authenticate(Request $request) {

        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect()->route('listings.index')->with('message', 'You are now logged in!');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }
}
