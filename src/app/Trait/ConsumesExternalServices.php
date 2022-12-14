<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalServices
{
  public function makeRequest($method, $requestUrl, $queryParams = [],
  $formParams = [], $headers = [])
  {
    $client = new Client([
      'base_url' => $this->baseUri,
    ]);

    $response = $client->request($method, $requestUrl, [
      'query' => $queryParams,
      'form_params' => $formParams,
      'headers' => $headers,
    ]);

    $response = $response->getBody()->getContents();

    if (method_exists($this, 'resolveAuthorization')) {
        $this->resolveAuthorization($queryParams, $formParams, $headers);
    }

    if (method_exists($this, 'decodeResponse')) {
        $response = $this->decodeResponse($response);
    }

    if (method_exists($this, 'checkIfErrorResponse')) {
        $this->checkIfErrorResponse($response);
    }

    return $response;
  }
}
