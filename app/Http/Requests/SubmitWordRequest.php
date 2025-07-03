<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitWordRequest extends FormRequest
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
            'word' => 'required|string|alpha|min:2|max:14',
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
            'word.required' => 'Word is required.',
            'word.alpha' => 'Word must contain only letters.',
            'word.min' => 'Word must be at least 2 characters.',
            'word.max' => 'Word cannot be more than 14 characters.',
        ];
    }
}
