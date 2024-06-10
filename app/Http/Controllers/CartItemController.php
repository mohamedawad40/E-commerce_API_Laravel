<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $cart = CartItem::where('user_id', $user->id)->get();

        return response()->json(['message' => 'Cart items retrieved successfully', 'cart_items' => $cart], 200);


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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $cartItem = CartItem::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Cart item created successfully', 'cart_item' => $cartItem], 201);

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
        $request->validate([
            'product_id'   =>  'required|integer',
            'quantity'   =>  'required|numeric',
        ]);

        $user=Auth::user();
        $cart=CartItem::where('user_id',$user->id)->findOrFail($id);
        $updated=$cart->update([
            'product_id'   =>  $request->product_id,
            'quantity'     =>  $request->quantity,
            'user_id'      =>  1,
        ]);
        if($updated){
            return response()->json(['message'  => 'The Cart is successfully updated'],201);
        }else{
            return response()->json(['message'  => 'Failed to update cart item'],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart=CartItem::findOrFail($id);
        $updated=$cart->delete();
        return response()->json(['message'  =>  'this cart item has successfully deleted', 'cartItem'  => $cart],201);
    }
}
