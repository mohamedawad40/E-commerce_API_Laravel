<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users=User::all();
        return response()->json(['message'  =>  'users are found', 'Users' => $users],201);
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
            'name'          =>   'required|max:255|String',
            'password'      =>   'required|min:4|String'
        ]);
        $user=User::create([
            'name'  =>  $request->name,
            'password'  =>  Hash::make($request->password),
        ]);
        return response()->json(['message'  => 'User has been created successfully' , 'user' => $user],201);
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
            'name'          =>   'required|max:255|String',
            'password'      =>   'required|min:4|String'
        ]);
        $user=User::findOrFail($id);
        // $updated=$user->update([
        //     'name'  =>  $request->name,
        //     'password'  =>  $request->password,
        // ]);
        $data=$request->only('name');
        if($request->filled('password')){
            $data['password'] =  Hash::make($request->password);
        }
        $user->update($data);
        return response()->json(['message'  => 'User has been updated successfully' , 'user' => $user],201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::findOrFail($id);
        $deleted=$user->delete();
        return response()->json(['message'  =>  'User has deleted successfully', 'user'  => $deleted],201);
    }
}
