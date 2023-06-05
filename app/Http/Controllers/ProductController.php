<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all()->sortByDesc('updated_at');
        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:16', 'unique:products'],
            'price' => ['required', 'numeric', 'between:1,99999'],
            'stripe_price_id' => ['nullable', 'string'],
            'subscription_name' => ['nullable', 'string'],
        ]);

        Product::create($attributes);
        return redirect()->route('products')->with('success', 'Product added');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $format_price = dollars($product->price);
        return view('product.edit', compact('product', 'format_price'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:16', Rule::unique('products', 'name')->ignore($product->id)],
            'price' => ['required', 'numeric', 'between:1,99999'],
            'stripe_price_id' => ['nullable', 'string'],
            'subscription_name' => ['nullable', 'string'],
        ]);

        $product->update($attributes);
        return redirect()->route('products')->with('success', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products')->with('danger', 'Product deleted');
    }
}
