<?php

namespace App\Traits;

trait AuthorizesmarketRequests
{
  public function resolveAuthorization($queryParams, $formParams, $headers)
  {
    $accessToken = $this->resolveAccessToken();

    $headers['Authorization'] = $accessToken;
  }

  public function resolveAccessToken()
  {
    // サイトから取得してきたPersonal Access Token
    return 'Bearer ~';
  }
}
