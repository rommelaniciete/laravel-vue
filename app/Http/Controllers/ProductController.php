<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all()->map(function ($product) {
            $product->product_image_url = $product->product_image_url ? asset('storage/' . $product->product_image_url) : null;
            return $product;
        });

        return Inertia::render('Products', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_image_url' => 'nullable|file|image|max:2048',
        ]);

        // Save uploaded file if exists
        if ($request->hasFile('product_image_url')) {
            $path = $request->file('product_image_url')->store('products', 'public');
            $validated['product_image_url'] = $path;
        }

        $product = Product::create($validated);

        return back()->with('success', 'Product created successfully.');

        // dd($request->all());
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
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
        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }
}
