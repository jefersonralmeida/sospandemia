<?php

namespace App\Http\Requests;

use App\Rules\Cnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class UpdateEntityRequest extends FormRequest
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
            // 'cnpj' => ['required', 'digits:14', new Cnpj(), 'unique:entities,cnpj'], - não estou autorizando mudança de cnpj
            'name' => 'filled|string|min:4',
            'legal_name' => 'filled|string|min:4',
            'description' => 'filled|string|min:10',
            'contact_info' => 'filled|string',
            'street_address' => 'filled|string:10',
            'district_id' => 'filled|int|exists:districts,id'
        ];
    }
}
