<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::all();
        return response()->json(['attributes' => $attributes]);
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
    public function store(Request $request,Product $product)
    {
        $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $attribute = $product->attributes()->create([
            'key' => $request->key,
            'value' => $request->value,
        ]);

        return response()->json(['message' => 'Attribute created successfully', 'attribute' => $attribute], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'key' => 'sometimes|required|string|max:255',
            'value' => 'sometimes|required|string|max:255',
        ]);

        $attribute->update([
            'key' => $request->key,
            'value' => $request->value,
        ]);

        return response()->json(['message' => 'Attribute updated successfully', 'attribute' => $attribute]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Attribute $attribute)
    {
        $attribute->delete();

        return response()->json(['message' => 'Attribute deleted successfully']);
    }
}
