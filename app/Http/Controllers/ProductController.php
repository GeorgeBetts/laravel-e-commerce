<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
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
        $products = Product::paginate(15);
        return response($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // get the validated data
        $validated = $request->validated();

        // save to the product
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
    public function update(StoreProductRequest $request, Product $product)
    {
        // get the validated data
        $validated = $request->validated();

        // save to the product
        $product->update($validated);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, 204);
    }
}
