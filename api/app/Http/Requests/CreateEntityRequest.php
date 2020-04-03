<?php

namespace App\Http\Requests;

use App\Rules\Cnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class CreateEntityRequest extends FormRequest
{

    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function filters()
    {
        return [
            'name' => 'uppercase',
            'legal_name' => 'uppercase',
            'city' => 'uppercase',
            'state' => 'uppercase',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cnpj' => ['required', 'digits:14', new Cnpj(), 'unique:entities,cnpj'],
            'name' => 'required|string|min:4',
            'legal_name' => 'required|string|min:4',
            'description' => 'required|string|min:10',
            'street_address' => 'required|string:10',
            'city' => 'required',
            'state' => ['required', Rule::in(config('constants.states'))],
        ];
    }
}
