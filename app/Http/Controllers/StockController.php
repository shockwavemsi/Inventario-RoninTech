<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Configuracion;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $productos = Producto::with('categoria', 'proveedor')->get();
        
        return view('admin.stock', compact('config', 'productos'));
    }
}
