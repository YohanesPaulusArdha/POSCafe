<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{

    public function index()
    {

        $orders = Order::with('user', 'details.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Orders', compact('orders'));
    }
}