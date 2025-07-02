<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{

    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());

        $baseQuery = StockIn::whereDate('created_at', $selectedDate);

        $totalStockIn = (clone $baseQuery)->sum('quantity');

        $mainQuery = $baseQuery;

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $mainQuery->where(function ($q) use ($searchTerm) {
                $q->whereHas('product', fn($sub) => $sub->where('name', 'LIKE', $searchTerm))
                    ->orWhereHas('supplier', fn($sub) => $sub->where('name', 'LIKE', $searchTerm));
            });
        }

        $stockIns = $mainQuery->with(['product', 'supplier', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('inventory.stock-in', compact(
            'stockIns',
            'products',
            'suppliers',
            'totalStockIn',
            'selectedDate'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            StockIn::create([
                'product_id' => $request->product_id,
                'supplier_id' => $request->supplier_id,
                'quantity' => $request->quantity,
                'remarks' => $request->remarks,
                'user_id' => Auth::id(),
            ]);

            $product = Product::find($request->product_id);
            $product->increment('stock', $request->quantity);
        });

        return redirect()->route('inventory.stock-in.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function () use ($stockIn) {
            $product = Product::find($stockIn->product_id);

            if ($product && $product->stock >= $stockIn->quantity) {
                $product->decrement('stock', $stockIn->quantity);
            }
            $stockIn->delete();
        });

        return redirect()->route('inventory.stock-in.index')->with('success', 'Catatan stok masuk berhasil dihapus.');
    }
}