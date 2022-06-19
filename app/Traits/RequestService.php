<?php

declare(strict_types = 1);

namespace App\Traits;

use GuzzleHttp\Client;

trait RequestService
{
    /**
     * @param       $method
     * @param       $requestUrl
     * @param array $formParams
     * @param array $headers
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $baseUri, $requestUrl, $formParams = [], $headers = []) : string
    {
        $client = new Client();
        $response = $client->request($method, $baseUri.$requestUrl,
            [
                'form_params' => $formParams,
                'headers' => $headers
            ]
        );

        return $response->getBody()->getContents();
    }
}
