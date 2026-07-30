<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return [
            'message' => 'Success',
            'data' => $products,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return [
            'message' => 'Product created',
            'data' => $product,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return [
            'message' => 'Success',
            'data' => $product,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return [
            'message' => 'Product updated',
            'data' => $product,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return [
            'message' => 'Product deleted',
        ];
    }
}
