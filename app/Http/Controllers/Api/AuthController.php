<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginCustomerRequest;
use App\Http\Requests\RegisterCustomerRequest;
use App\Models\Customer;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param RegisterCustomerRequest $request
     * @return \App\Http\Resources\Customer|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterCustomerRequest $request)
    {
        try {
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            event(new Registered($customer));
            return new \App\Http\Resources\Customer($customer);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param LoginCustomerRequest $request
     * @return \App\Http\Resources\Customer|\Illuminate\Http\JsonResponse
     */
    public function login(LoginCustomerRequest $request)
    {
        try {
            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $customer = Customer::where('email', $request->email)->first();
            return new \App\Http\Resources\Customer($customer);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
