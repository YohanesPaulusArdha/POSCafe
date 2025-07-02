<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm, $request) {
                $q->where('name', 'LIKE', $searchTerm)
                    ->orWhereHas('category', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', $searchTerm);
                    })
                    ->orWhereHas('supplier', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', $searchTerm);
                    });
            });
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);

        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('masterdata.product', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($validated);

        return redirect()->route('master.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('master.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('master.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}