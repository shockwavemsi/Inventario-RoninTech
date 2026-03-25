<?php

namespace App\Http\Controllers;

use App\Models\Configuracion; 

class AdminController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        return view('admin.index', compact('config'));
    }
}


