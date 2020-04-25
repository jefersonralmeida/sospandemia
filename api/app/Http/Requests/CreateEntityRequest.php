<?php

namespace App\Http\Requests;

use App\Models\EntityTypes\EntityTypeContract;
use App\Rules\Cnpj;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Waavi\Sanitizer\Laravel\SanitizesInput;

/**
 * Class CreateEntityRequest
 * @package App\Http\Requests
 * @property string name
 * @property string legal_name
 * @property int entity_type_id
 * @property int entity_type_document
 * @property int district_id
 */
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
            'legal_name' => 'uppercase'
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
            'entity_type_id' => ['required', 'integer', 'min:0', Rule::in(array_keys(config('entity_types')))],
            'entity_type_document' => [
                'bail',
                'filled',
                Rule::requiredIf(function() {
                    return !empty(config("entity_types.{$this->entity_type_id}.entity_type_document"));
                }),
                // validades the document based on the custom entity type
                function ($attribute, $value, $fail) {
                    $class = config("entity_types.{$this->entity_type_id}.class");
                    if ($class === null) {
                        $fail("Tipo de entidade inválido.");
                        return;
                    }
                    /** @var EntityTypeContract $entityType */
                    $entityType = app($class);
                    if (!$entityType->validate($this, $errorMessage)) {
                        $fail($errorMessage);
                    }
                },
            ],
            'cnpj' => [
                'required',
                'digits:14', new Cnpj(),
                Rule::unique('entities', 'cnpj')->where(function (Builder $query) {
                    $query->where('entity_type_document', $this->entity_type_document);
                }),
            ],
            'name' => 'required|string|min:4',
            'legal_name' => 'required|string|min:4',
            'description' => 'required|string|min:10',
            'contact_info' => 'filled|string',
            'street_address' => 'required|string:10',
            'district_id' => 'required|int|exists:districts,id',
        ];
    }
}
