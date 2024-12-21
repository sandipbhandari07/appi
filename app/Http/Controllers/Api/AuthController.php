<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseHelper;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * register user
     */
    public function register(RegisterRequest $request)
    {
        try {
            $responseHelper = new ResponseHelper(); // Instantiate ResponseHelper
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);
    
            if ($user) {
                return $responseHelper->success(
                    message: 'User created successfully',
                    data: $user,
                    statusCode: 200
                );
            }
    
            return $responseHelper->error(
                message: 'Failed to create user',
                statusCode: 500
            );
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
    
            $responseHelper = new ResponseHelper();
            return $responseHelper->error(
                message: 'An error occurred while creating the user',
                statusCode: 500
            );
        }
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
