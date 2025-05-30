<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function redirectToFrontend()
    {
        return redirect('http://localhost:8080');
    }
} 