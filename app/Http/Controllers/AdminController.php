<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin=Admin::all();
        return response()->json(['message'  =>  'admin is found',   'admin'=>$admin],201);
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
        $request->validate([
            'name'      =>  'required|String|max:255',
            'password'      =>  'required|String|min:6',
        ]);
        $admin=Admin::create([
            'name'          =>  $request->name,
            'password'      =>  Hash::make($request->password)
        ]);
        return response()->json(['message'  =>  'admin is created',   'admin'=>$admin],201);

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
            'name'      =>  'required|String|max:255',
            'password'      =>  'required|String|min:6',
        ]);

        $admin=Admin::findOrFail($id);
        $data=$request->only('name');
        if($request->filled('password')){
            $data['password']=Hash::make($request->password);
        }

        $admin->update($data);
        return response()->json(['message'  =>  'admin is updated successfully',   'admin'=>$admin],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin=Admin::findOrFail($id);
        $admin->delete();
        return response()->json(['message'  =>  'admin is updated deleted',   'admin'=>$admin],201);
    }
}
