<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function show_home_page() {
        return redirect('/login');
    }
}
