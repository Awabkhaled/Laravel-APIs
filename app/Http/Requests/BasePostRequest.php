<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class BasePostRequest extends FormRequest
{
    /**
     * An Attributes to choose the rules to validate on
     */
    protected array $returnedRules = [];

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
        return $this->returnedRules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors()->all()
            ], 400,[],JSON_PRETTY_PRINT)
        );
    }

    public function messages()
    {
        return [
            'tags.*.integer' => 'Invalid tag ID.',
            'tags.*.exists' => 'One or more of the tag IDs do not exist.',
        ];
    }
}
