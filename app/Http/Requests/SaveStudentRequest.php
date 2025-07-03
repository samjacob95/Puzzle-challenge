<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveStudentRequest extends FormRequest
{
    /**
     * Decide whether this action is authorised
     *
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define the validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|alpha|min:2|max:256',
        ];
    }

    /**
     * Define the error messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'name.alpha' => 'Student name must contain only letters.',
            'name.min' => 'Student name must be at least 2 characters.',
            'name.max' => 'Student name cannot be more than 256 characters.',
        ];
    }
}
