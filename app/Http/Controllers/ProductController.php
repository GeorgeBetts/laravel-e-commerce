<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        // Validate query params
        $request->validate([
            'search' => 'string|max:255',
            'sort_column' => ['string', 'required_with:sort_direction'],
            'sort_direction' => [Rule::in(['ASC', 'DESC']), 'required_with:sort_column'],
        ]);

        $query = DB::table('products');

        // Filter on product name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort query
        if ($request->has('sort_column') && $request->has('sort_direction')) {
            $query->orderBy($request->sort_column, $request->sort_direction);
        }

        $products = $query->paginate(15);

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
        $product = new Product($validated);
        $product->user_id = Auth::user()->id;
        $product->save();

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
        // check user is authorised
        $this->authorize('update', $product);

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
