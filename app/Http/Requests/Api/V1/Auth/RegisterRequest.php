<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Traits\V1\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{

    use ApiResponse;

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
            'email'      => "required|email|unique:users",
            'first_name' => "required|string",
            'last_name'  => "required|string",
            'password'   => "required|confirmed",
            // 'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:15360',
            // 'date_of_birth' => 'required|date|before_or_equal:today',
        ];
    }

    /**
     * Define the custom validation error messages.
     *
     * @return array The custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string'   => 'First name must be a string.',

            'last_name.required' => 'Last name is required.',
            'last_name.string'   => 'Last name must be a string.',

            'email.required' => 'Email address is required.',
            'email.email'    => 'Email address must be a valid email format.',
            'email.unique'   => 'This email is already taken.',

            'password.required'  => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',

            // 'avatar.image' => 'The uploaded file must be an image.',
            // 'avatar.mimes' => 'Only jpeg, png, jpg, gif, svg, and webp formats are allowed.',
            // 'avatar.max'   => 'The image must not be greater than 15MB.',

            // 'date_of_birth.required'        => 'The date of birth is required.',
            // 'date_of_birth.date'            => 'The date of birth must be a valid date.',
            // 'date_of_birth.before_or_equal' => 'The date of birth must be today or in the past.',
        ];
    }



    /**
     * failedValidation
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Validation\ValidationException
     * @return never
     */
    protected function failedValidation(Validator $validator): never
    {
        $fieldsToCheck = [
            'first_name',
            'last_name',
            'email',
            'password',
            // 'avatar',
            // 'date_of_birth',
        ];
        $message = 'Validation error'; // Default message

        foreach ($fieldsToCheck as $field) {
            $errors = $validator->errors()->get($field);
            if (!empty($errors)) {
                $message = $errors[0];
                break;
            }
        }

        $response = $this->error(
            422,
            $message,
            $validator->errors(),
        );

        throw new ValidationException($validator, $response);
    }
}
