<?php

namespace App\Http\Requests;

use App\Models\Demand;
use App\Models\Entity;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CreateDemandRequest
 * @package App\Http\Requests
 * @property string title
 * @property string text
 * @property int entity_id
 */
class UpdateDemandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws HttpException
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'filled',
            'text' => 'filled',
            'contact_info' => 'filled|string',
            'quantity' => 'filled|int|min:0',
            'unit' => 'filled|string',
        ];
    }
}
