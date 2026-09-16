<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigoBarras' => 'required|string',
            'nombre' => 'required|string',
            'material' => 'required|string',
            'presentacion' => 'required|string',
            'ecoPoints' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $validated['activo'] = $request->has('activo');

        try {
            $this->productRepository->create($validated);
            return redirect()->route('admin.settings.index')->with('success', 'Producto agregado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear producto: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'material' => 'required|string',
            'presentacion' => 'required|string',
            'ecoPoints' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $validated['activo'] = $request->has('activo');
        // El codigo de barras no debería cambiarse (es el ID), si se cambia se debería crear otro.
        $validated['codigoBarras'] = $id;

        try {
            $this->productRepository->update($id, $validated);
            return redirect()->route('admin.settings.index')->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar producto: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->productRepository->delete($id);
            return redirect()->route('admin.settings.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar producto: ' . $e->getMessage());
        }
    }
}
