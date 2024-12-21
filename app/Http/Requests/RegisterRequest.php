<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => "required|max:100|min:5",
            'email' => "required|email|unique:users",
            'password' => "required|min:8|max:15",
            'phone_number' => "required|digits:10",
        ];
    }
    public function messages(): array
    {   
        return [    
            'name.required' => 'Name is required',
            'name.max' => 'Name must be at most 100 characters',
            'name.min' => 'Name must be at least 5 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.max' => 'Password must be at most 15 characters',
            'phone_number.required' => 'Phone number is required',
            'phone_number.digits' => 'Phone number must be 10 digits',
        ];
    }
}
