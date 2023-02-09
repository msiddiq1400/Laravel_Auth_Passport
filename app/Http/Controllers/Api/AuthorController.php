<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:authors,email,except,id',
            'password' => 'required|confirmed',
            'phone_no' => 'required',
        ]);

        $author = new Author();
        $author->name = $request->name;
        $author->email = $request->email;
        $author->password = bcrypt($request->password);
        $author->phone_no = $request->phone_no;
        $author->save();

        return response()->json([
            "status" => 1,
            "message" => "Author Created",
            "data" => $author
        ], 201);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json([
                "status" => false,
                "message" => "Invalid Credentials"
            ], 401);
        }

        /** @var \App\Models\Author */
        $user = auth()->user();
        
        $token = $user->createToken('auth_token');
        return response()->json([
            "status" => true,
            "message" => "User Logged In",
            "access_token" => $token,
            "user" => auth()->user()
        ], 200);

    }

    public function profie()
    {
        
    }

    public function logout()
    {
        
    }
}