<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterResidentRequest extends FormRequest
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
            'phone'           => 'required|string|max:20',
            'name'            => 'required|string|max:200',
            'email'           => 'required|string|email|max:255|unique:users,email',
            'address'         => 'nullable|string|max:500',
            'invitation_code' => 'nullable|string|max:500',
            'password'        => 'required|string|min:8|max:150|confirmed',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'cover_photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10096',
            'nid_file'        => 'nullable|array',
            'nid_file.*'      => 'image|mimes:jpeg,png,jpg,gif,svg|max:3048',
        ];
    }

    /**
     * Custom validation messages.
     */

    public function messages(): array
    {
        return [
            'phone.required'     => 'The phone number field is required.',
            'name.required'      => 'The name field is required.',
            'name.string'        => 'The name must be a valid string.',
            'email.required'     => 'The email field is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email address is already registered.',
            'address.string'     => 'The address must be a valid string.',
            'password.required'  => 'The password field is required.',
            'password.min'       => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'avatar.image'       => 'The avatar must be an image file.',
            'avatar.mimes'       => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
            'cover_photo.image'  => 'The cover photo must be an image file.',
            'cover_photo.mimes'  => 'The cover photo must be a file of type: jpeg, png, jpg, gif.',
            'nid_file.required'  => 'At least one NID file is required.',
            'nid_file.array'     => 'The NID files must be an array.',
            'nid_file.min'       => 'You must upload at least one NID file.',
            'nid_file.max'       => 'You can upload up to 3 NID files.',
            'nid_file.*.image'   => 'Each NID file must be an image.',
            'nid_file.*.mimes'   => 'Each NID file must be of type: jpeg, png, jpg, gif, svg.',
            'nid_file.*.max'     => 'Each NID file may not be greater than 3MB.',
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
