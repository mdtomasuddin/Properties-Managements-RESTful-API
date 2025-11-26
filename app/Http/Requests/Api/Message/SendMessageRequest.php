<?php
namespace App\Http\Requests\Api\Message;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
     *check
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'message'   => 'nullable|string|max:500',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ];
    }

    //validation message
    public function messages()
    {
        return [
            'message.max'     => 'Message should not exceed 500 characters',
            'file_path.file'  => 'The uploaded file must be a valid file',
            'file_path.mimes' => 'Only jpeg, png, jpg, gif, svg images are allowed',
            'file_path.max'   => 'File size should not exceed 5MB',
        ];
    }

}
