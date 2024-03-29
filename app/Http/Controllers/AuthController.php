<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try 
        {

            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) 
            {
                return response()->json(['user_not_found'], 404);
            }

        }
        catch (\Tymon\JWTAuth\Exceptions\JWTException $e) 
        {

            return response()->json(['error' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }
}
