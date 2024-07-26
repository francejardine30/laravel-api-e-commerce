<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Creates a user using inputs from request
     * POST: /api/signup
     * @param Request
     * @return Response
     */
    public function signup(Request $request)
    {
        $validator = validator($request->all(), [
            'username' => 'required|unique:users|min:5|string',
            'email' => 'required|unique:users|email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::create($request->all());

        return response()->json([
            'data' => $user,
            'message' => 'User created successfully!'
        ], 201);
    }


    /**
     * Sign in a user using inputs from request
     * POST: /api/signin
     * @param Request
     * @return Response
     */

    public function signin(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        if (!auth()->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Invalid credentials!',
            ], 401);
        }

        $user = auth()->user();

        return response()->json([
            'data' => $user,
        ], 200);
    }
}
