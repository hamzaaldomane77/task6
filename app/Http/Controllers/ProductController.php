<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json(
            [
                'status' => 'success',
                'products' => $products,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            DB::beganTransaction();
            $products = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,

            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'products' => $products,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $request->validate([
            'name' => 'nullable|string',
            'description' => 'nullable|string|max: 255',
            'price' => 'nullable|numeric',
        ]);
        $products = Product::update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,

        ]);
        return response()->json(
            [
                'status' => 'success',
                'products' => $products,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'status' => 'success',
            'product' => $product,
        ]);
    }
}
