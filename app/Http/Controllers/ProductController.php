<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $products=Product::all();
        $products = Product::with('categories')->get();
        return response()->json(['message' => 'Product is found', 'product' => $products], 201);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id', // Ensure each category exists
            'attributes' => 'nullable|array',
            'attributes.*.key' => 'required|string|max:255',
            'attributes.*.value' => 'required|string|max:255',
        ]);

        $imagepath=$request->has('image')? $request->file('image')->store('images','public') :null;

        $product=Product::create([
            'name'         => $request->name,
            'description'  => $request->description,
            'price'  => $request->price,
            'image'  => $imagepath,
        ]);

        $product->categories()->attach($validated['categories']);

        // // Add attributes if present
        // if (!empty($validated['attributes'])) {
        //     foreach ($validated['attributes'] as $attribute) {
        //         $product->attributes()->create([
        //             'key' => $attribute['key'],
        //             'value' => $attribute['value'],
        //         ]);
        //     }
        // }
        if(!empty($validated('attributes'))){
            foreach ($validated('attributes') as $attribute) {
                $product->attributes()->create([
                    'key'       =>  $attribute->key,
                    'value'       =>  $attribute->value,
                ]);
            }
        }
        return response()->json(['message' => 'Product is successfully created', 'product' => $product], 201);

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
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|image',
            'categories' => 'sometimes|required|array',
            'categories.*' => 'exists:categories,id',
            'attributes' => 'nullable|array',
            'attributes.*.key' => 'required_with:attributes|string|max:255',
            'attributes.*.value' => 'required_with:attributes|string|max:255',
        ]);

        // Find the product by ID
        $product = Product::findOrFail($id);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        if (isset($validated['categories'])) {
            $product->categories()->sync($validated['categories']);
            }

        // Update attributes if present
        // if (!empty($validated['attributes'])) {
        //     // Delete existing attributes
        //     $product->attributes()->delete();
        //     // Add new attributes
        //     foreach ($validated['attributes'] as $attribute) {
        //         $product->attributes()->create([
        //             'key' => $attribute['key'],
        //             'value' => $attribute['value'],
        //         ]);
        //     }
        // }


        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product=Product::findOrFail($id);
        if($product->image){
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json(['message' => 'Product is deleted Successfully', 'product' => $product], 201);

    }
}
