<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterPersonalInfoRequest extends FormRequest
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
            'phone'       => 'required',
            'name'        => 'required|string|max:200',
            'email'       => 'nullable|email|max:200',
            'address'     => 'nullable|string|max:500',
            'password'    => 'required|string|min:8|max:150|confirmed',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4048',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ];
    }

    /**
     * Custom validation messages.
     */

    public function messages(): array
    {
        return [
            'phone.required'     => 'The phone Number field is required.',
            'name.required'      => 'The name field is required.',
            'name.string'        => 'The name must be a valid string.',
            'email.email'        => 'Please enter a valid email address.',
            'address.required'   => 'The address field is required.',
            'password.required'  => 'The password field is required.',
            'password.min'       => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'avatar.image'       => 'The avatar must be an image file.',
            'avatar.mimes'       => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
            'cover_photo.image'  => 'The cover photo must be an image file.',
            'cover_photo.mimes'  => 'The cover photo must be a file of type: jpeg, png, jpg, gif.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): never
    {
        $errors  = $validator->errors();
        $message = $errors->first();

        $response = response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], 422);

        throw new HttpResponseException($response);
    }
}
