<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $products = Product::all();
        return response($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the request data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $product = Product::create($validated);

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
