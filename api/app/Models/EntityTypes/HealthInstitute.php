<?php

namespace App\Models\EntityTypes;

use App\ExternalApi\CnesApi;
use App\Http\Requests\CreateEntityRequest;
use App\Models\District;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HealthInstitute implements EntityTypeContract
{

    /**
     * @var CnesApi
     */
    protected $api;

    public function __construct(CnesApi $api)
    {
        $this->api = $api;
    }

    /**
     * @param CreateEntityRequest $request
     * @param string|null $error
     * @return bool
     */
    public function validate(Request $request, ?string &$error = '')
    {
        $error = '';

        try {
            $response = $this->api->search([
                'cnes' => $request->entity_type_document
            ]);
        } catch (ConnectException $e) {
            $error = 'Serviço de validação de CNES indisponível, tente novamente mais tarde.';
            return false;
        }
        $filtered = array_filter($response, fn($item) => $item['cnes'] === $request->entity_type_document);
        if (empty($filtered)) {
            $error = 'CNES não encontrado';
            return false;
        }

        $result = array_shift($filtered);
        if (trim($result['noFantasia']) !== $request->name) {
            $error = 'O nome informado não bate com o CNES informado.';
            return false;
        }
        if (trim($result['noEmpresarial']) !== $request->legal_name) {
            $error = 'A razão social informada não bate com o CNES informado.';
            return false;
        }
        $district = District::find($request->district_id);
        if (trim($result['noMunicipio']) !== strtoupper($district->municipio) || trim($result['uf']) !== $district->uf) {
            $error = 'A localização informada não bate com o CNES informado';
            return false;
        }
        return true;
    }

    /**
     * @param array $query
     * @return array
     */
    public function search(array $query)
    {
        try {
            $result = $this->api->search($query);
        } catch (ConnectException $e) {
            throw new HttpException(503, 'Serviço de validação de CNES indisponível, tente novamente mais tarde.');
        } catch (\Throwable $e) {
            throw new HttpException(400, 'Invalid request');
        }

        if (empty($result)) {
            return [];
        }

        $result = array_shift($result);

        return [
            'cnes' => $result['cnes'],
            'name' => $result['noFantasia'],
            'legal_name' => $result['noEmpresarial'],
            'uf' => $result['uf'],
            'district_name' => $result['noMunicipio']
        ];
    }
}
