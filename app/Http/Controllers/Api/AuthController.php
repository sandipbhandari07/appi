<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helper\ResponseHelper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;



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
                statusCode: 400
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



    public function login(LoginRequest $request)
    {
        try{
            //if credentials are invalid
            if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return ResponseHelper::error(message: 'Invalid credentials', statusCode: 400);
            }

            $user = Auth::user();
            //creating token
            $token = $user->createToken('API Token')->plainTextToken;
            $authUser = [
                'user' => $user,
                'token' => $token
            ];
            return ResponseHelper::success(message: 'Login successful', data: $authUser, statusCode: 200);

        }
        catch(Exception $e){

            Log::error('Error creating user: '. $e->getMessage());
            return ResponseHelper::error(message: 'An error occurred while creating the user', statusCode: 500);
        }
    }
    public function userProfile()
    {
       try{
          $user = Auth::user();
          if($user){
            return ResponseHelper::success(message: 'Login successful', data: $user, statusCode: 200);
          }

       }
       catch(Exception $e){
        Log::error('Error creating user: '. $e->getMessage());
        return ResponseHelper::error(message: 'An error occurred while creating the user', statusCode: 500);
       }
    }
    public function userLogout()
    {
        try{
            $user = Auth::user();
            $user->currentAccessToken()->delete();
            return ResponseHelper::success(message: 'Logout successful', data: [], statusCode: 200);
        }
        catch(Exception $e){
            Log::error('Error creating user: '. $e->getMessage());
            return ResponseHelper::error(message: 'An error occurred while creating the user', statusCode: 500);
        }
    }
}
