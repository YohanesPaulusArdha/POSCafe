<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();

        $paidOrders = Order::where('status', 'Completed')
            ->whereDate('created_at', Carbon::today())
            ->with('user')
            ->latest()
            ->get();



        return view('POS', [
            'products' => $products,
            'categories' => $categories,
            'paidOrders' => $paidOrders,

        ]);
    }

    public function storeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:product,id',
            'items.*.quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'service' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'amount_paid' => 'nullable|numeric',
            'change' => 'nullable|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }


        DB::beginTransaction();
        try {
            $order = Order::create([
                'invoice_number' => 'INV-' . time() . '-' . Str::upper(Str::random(4)),
                'user_id' => auth()->id(),
                'total_amount' => $request->total,
                'amount_paid' => $request->amount_paid ?? $request->total,
                'change' => $request->change ?? 0,
                'payment_method' => $request->payment_method,
                'status' => 'Completed',
            ]);
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pesanan berhasil disimpan!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan pesanan.'], 500);
        }
    }
}