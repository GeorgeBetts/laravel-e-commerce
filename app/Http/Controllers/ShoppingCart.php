<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShoppingCart extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = session('cart') ?? [];
        return response()->json($cart);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = session('cart') ?? [];

        // Validate request data
        $validated = $request->validate([
            'product_id' => ['required', 'numeric', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
        ]);

        // Add to cart
        $cart[] = $validated;

        // Update cart in session
        session()->put('cart', $cart);

        return response()->json(session('cart') ?? []);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        session()->put('cart', []);

        return response(null, 204);
    }
}
