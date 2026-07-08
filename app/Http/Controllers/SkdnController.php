<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SkdnController extends Controller
{
    public function index()
    {
        return view('admin.skdn.index');
    }
}
