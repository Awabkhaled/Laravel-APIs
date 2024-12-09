<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rule as NewRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class TagRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $currentTagId = $this->route("tag");
        return [
            'name' => [
                'required',
                'string',
                NewRule::unique('tags','name')->ignore($currentTagId),
                'max:255',
            ],
        ];
    }

    public function messages():array
    {
        return [
            'name.required' => 'The name is required',
            'name.unique' => 'The name is already taken',
            'name.string' => 'The name must be a string',
            'name.max' => 'The name size has to be less than or equal to 255 letter',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors()->all()
            ], 400,[],JSON_PRETTY_PRINT)
        );
    }
}
