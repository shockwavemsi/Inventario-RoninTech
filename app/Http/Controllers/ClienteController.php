<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $config = Configuracion::first();
        $buscar = trim((string) $request->query('buscar', ''));

        $clientes = Cliente::query()
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellido', 'like', "%{$buscar}%")
                        ->orWhere('documento', 'like', "%{$buscar}%")
                        ->orWhere('telefono', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.clientes', compact('config', 'clientes', 'buscar'));
    }

    public function buscar(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $clientes = Cliente::query()
            ->where('activo', true)
            ->when($query !== '', function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                    ->orWhere('apellido', 'like', "%{$query}%")
                    ->orWhere('documento', 'like', "%{$query}%")
                    ->orWhereRaw("CONCAT(nombre, ' ', apellido) LIKE ?", ["%{$query}%"]);
                });
            })
            ->when($query === '', fn ($q) => $q->orderBy('id', 'desc'))
            ->when($query !== '', fn ($q) => $q->orderBy('nombre'))
            ->limit($query === '' ? 5 : 8)
            ->get()
            ->map(fn (Cliente $cliente) => $this->formatCliente($cliente));

        return response()->json($clientes);
    }

    public function show($id)
    {
        return response()->json($this->formatCliente(Cliente::findOrFail($id)));
    }

    public function store(Request $request)
    {
        $data = $this->validateCliente($request);
        $cliente = Cliente::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'cliente' => $this->formatCliente($cliente),
            ], 201);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente');
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $data = $this->validateCliente($request, $cliente->id);
        $cliente->update($data);

        return response()->json([
            'success' => true,
            'cliente' => $this->formatCliente($cliente->fresh()),
        ]);
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    private function validateCliente(Request $request, ?int $clienteId = null): array
    {
        return $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'documento' => ['required', 'string', 'max:20', Rule::unique('clientes', 'documento')->ignore($clienteId)],
            'telefono' => 'required|string|max:20',
            'activo' => 'nullable|boolean',
        ]) + ['activo' => $request->has('activo') ? $request->boolean('activo') : $clienteId === null];
    }

    private function formatCliente(Cliente $cliente): array
    {
        return [
            'id' => (int) $cliente->id,
            'nombre' => $cliente->nombre,
            'apellido' => $cliente->apellido,
            'nombre_completo' => trim($cliente->nombre . ' ' . $cliente->apellido),
            'documento' => $cliente->documento,
            'telefono' => $cliente->telefono,
            'activo' => (bool) $cliente->activo,
        ];
    }
}
