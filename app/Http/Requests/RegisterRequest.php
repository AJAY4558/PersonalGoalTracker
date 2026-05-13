<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterRequest
 * Validates user registration form input.
 * Demonstrates Laravel Form Request validation.
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Guests (unauthenticated) can register.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for registration.
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Custom error messages (beginner-friendly).
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'Please enter your full name.',
            'name.min'           => 'Name must be at least 2 characters.',
            'email.required'     => 'Please enter your email address.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email is already registered. Try logging in.',
            'password.required'  => 'Please choose a password.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}
