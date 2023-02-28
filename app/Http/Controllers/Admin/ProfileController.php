<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function logout() {
       auth()->logout();
       return  redirect()->route('getLogin')->with('success', 'You have been successfully logged out');
    }


}
