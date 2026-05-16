<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\FormaPago;
use App\Models\Banco;
use App\Models\FormasPagoProveedor;
use App\Models\DiasVencimientoProveedor;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $proveedores = Proveedor::with('formasPago.formaPago', 'formasPago.banco', 'diasVencimiento')->get();
        $formasPago = FormaPago::where('activo', true)->get();
        $bancos = Banco::all();

        return view('admin.proveedores', compact('config', 'proveedores', 'formasPago', 'bancos'));
    }

    public function create()
    {
        $config = Configuracion::first();
        $formasPago = FormaPago::where('activo', true)->get();
        $bancos = Banco::all();

        return view('admin.proveedores_crear', compact('config', 'formasPago', 'bancos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'contacto_nombre' => 'nullable|string|max:100',
            'contacto_telefono' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'ruc' => 'nullable|string|max:20',
            'activo' => 'nullable|boolean',
            'dias_vencimiento' => 'required|integer|min:1|max:365',
            'forma_pago_id' => 'required|exists:formas_pago,id',
            'banco_id' => 'nullable|exists:bancos,id',
            'referencia' => 'nullable|string|max:255',
            'nombre_banco' => 'nullable|string|max:255',
        ]);

        try {
            // Crear proveedor
            $proveedor = Proveedor::create([
                'nombre' => $request->nombre,
                'ruc' => $request->ruc,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'contacto_nombre' => $request->contacto_nombre,
                'contacto_telefono' => $request->contacto_telefono,
                'activo' => $request->activo ?? true,
            ]);

            // Crear días de vencimiento
            DiasVencimientoProveedor::create([
                'proveedor_id' => $proveedor->id,
                'dias_vencimiento' => $request->dias_vencimiento,
            ]);

            // Crear forma de pago del proveedor
            FormasPagoProveedor::create([
                'proveedor_id' => $proveedor->id,
                'forma_pago_id' => $request->forma_pago_id,
                'banco_id' => $request->banco_id,
                'referencia' => $request->referencia,
                'nombre_banco' => $request->nombre_banco,
            ]);

            return redirect()->route('proveedores.index')->with('success', '✅ Proveedor creado con éxito');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error al crear proveedor: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $proveedor = Proveedor::with('formasPago.formaPago', 'formasPago.banco', 'diasVencimiento')->findOrFail($id);
        return response()->json($proveedor);
    }

    public function edit($id)
    {
        $proveedor = Proveedor::with('formasPago', 'diasVencimiento')->findOrFail($id);
        return response()->json($proveedor);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:200',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'contacto_nombre' => 'nullable|string|max:100',
            'contacto_telefono' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'ruc' => 'nullable|string|max:20',
            'dias_vencimiento' => 'nullable|integer|min:1|max:365',
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());

        // Actualizar días de vencimiento
        if ($request->has('dias_vencimiento')) {
            DiasVencimientoProveedor::updateOrCreate(
                ['proveedor_id' => $id],
                ['dias_vencimiento' => $request->dias_vencimiento]
            );
        }

        return response()->json([
            'success' => true,
            'message' => '✅ Proveedor actualizado correctamente'
        ]);
    }

    public function destroy($id)
    {
        try {
            Proveedor::findOrFail($id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function getFormasPago()
    {
        $formasPago = FormaPago::where('activo', true)->get();
        return response()->json($formasPago);
    }
}