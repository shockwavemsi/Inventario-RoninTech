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
        $proveedores = Proveedor::with('formasPago.formaPago', 'formasPago.banco', 'diasVencimiento')
            ->get();
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
        // ✅ VALIDAR DATOS GENERALES
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
            // ✅ VALIDAR ARRAYS DE FORMAS DE PAGO
            'forma_pago_id' => 'required|array|min:1',
            'forma_pago_id.*' => 'required|exists:formas_pago,id',
            'banco_id' => 'nullable|array',
            'banco_id.*' => 'nullable|exists:bancos,id',
            'referencia' => 'nullable|array',
            'referencia.*' => 'nullable|string|max:255',
            'nombre_banco' => 'nullable|array',
            'nombre_banco.*' => 'nullable|string|max:255',
        ]);

        try {
            // ✅ CREAR PROVEEDOR
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

            // ✅ CREAR DÍAS DE VENCIMIENTO
            DiasVencimientoProveedor::create([
                'proveedor_id' => $proveedor->id,
                'dias_vencimiento' => $request->dias_vencimiento,
            ]);

            // ✅ CREAR MÚLTIPLES FORMAS DE PAGO (LOOP)
            $formasPagoIds = $request->input('forma_pago_id', []);
            $bancosIds = $request->input('banco_id', []);
            $referencias = $request->input('referencia', []);
            $nombresBancos = $request->input('nombre_banco', []);

            foreach ($formasPagoIds as $index => $formaPagoId) {
                FormasPagoProveedor::create([
                    'proveedor_id' => $proveedor->id,
                    'forma_pago_id' => $formaPagoId,
                    'banco_id' => $bancosIds[$index] ?? null,
                    'referencia' => $referencias[$index] ?? null,
                    'nombre_banco' => $nombresBancos[$index] ?? null,
                ]);
            }

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
        $proveedor = Proveedor::with('formasPago.formaPago', 'formasPago.banco', 'diasVencimiento')->findOrFail($id);
        return response()->json($proveedor);
    }

    public function update(Request $request, $id)
    {
        // ✅ VALIDAR DATOS GENERALES
        $request->validate([
            'nombre' => 'required|string|max:200',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'contacto_nombre' => 'nullable|string|max:100',
            'contacto_telefono' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'ruc' => 'nullable|string|max:20',
            'dias_vencimiento' => 'nullable|integer|min:1|max:365',
            // ✅ VALIDAR ARRAYS DE FORMAS DE PAGO (OPCIONAL EN EDICIÓN)
            'forma_pago_id' => 'nullable|array|min:1',
            'forma_pago_id.*' => 'nullable|exists:formas_pago,id',
            'banco_id' => 'nullable|array',
            'banco_id.*' => 'nullable|exists:bancos,id',
            'referencia' => 'nullable|array',
            'referencia.*' => 'nullable|string|max:255',
            'nombre_banco' => 'nullable|array',
            'nombre_banco.*' => 'nullable|string|max:255',
        ]);

        try {
            $proveedor = Proveedor::findOrFail($id);

            // ✅ ACTUALIZAR DATOS GENERALES
            $proveedor->update([
                'nombre' => $request->nombre,
                'ruc' => $request->ruc,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'contacto_nombre' => $request->contacto_nombre,
                'contacto_telefono' => $request->contacto_telefono,
                'activo' => $request->activo ?? true,
            ]);

            // ✅ ACTUALIZAR DÍAS DE VENCIMIENTO
            if ($request->has('dias_vencimiento')) {
                DiasVencimientoProveedor::updateOrCreate(
                    ['proveedor_id' => $id],
                    ['dias_vencimiento' => $request->dias_vencimiento]
                );
            }

            // ✅ ACTUALIZAR FORMAS DE PAGO (SI SE ENVÍAN)
            if ($request->has('forma_pago_id') && is_array($request->forma_pago_id)) {
                // 1. Eliminar todas las formas de pago existentes
                FormasPagoProveedor::where('proveedor_id', $id)->delete();

                // 2. Crear nuevas formas de pago
                $formasPagoIds = $request->input('forma_pago_id', []);
                $bancosIds = $request->input('banco_id', []);
                $referencias = $request->input('referencia', []);
                $nombresBancos = $request->input('nombre_banco', []);

                foreach ($formasPagoIds as $index => $formaPagoId) {
                    if ($formaPagoId) { // Solo si hay forma de pago seleccionada
                        FormasPagoProveedor::create([
                            'proveedor_id' => $id,
                            'forma_pago_id' => $formaPagoId,
                            'banco_id' => $bancosIds[$index] ?? null,
                            'referencia' => $referencias[$index] ?? null,
                            'nombre_banco' => $nombresBancos[$index] ?? null,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => ' Proveedor actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            // ✅ SOFT DELETE: Marcar como inactivo en lugar de eliminar
            $proveedor->update(['activo' => false]);
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
