<?php

namespace App\ExternalApi;

use GuzzleHttp\Client;

class CnesApi
{

    protected $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function search(array $query): array
    {
        $response = $this->httpClient->get('http://cnes.datasus.gov.br/services/estabelecimentos', [
            'query' => $query,
            'headers' => [
                'Referer' => 'http://cnes.datasus.gov.br/pages/estabelecimentos/consulta.jsp'
            ]

        ]);
        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }
}
