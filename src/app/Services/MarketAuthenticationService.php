<?php
// HTTP Clientからアクセストークンを取得するサービスの追加

namespace App\Services;

use App\Traits\AuthorizesmarketRequests;
use App\Traits\ConsumesExternalServices;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;

class MarketAuthenticationService
{
  use ConsumesExternalServices, InteractsWithDeprecationHandling;

    /**
     * The URL to send the requests
     * @var string
     */
    protected $baseUri;

    /**
     * The client id to identify the client in ther API
     * @var string
     */
    protected $clientId;

    /**
     * The client secret to identify the client in ther API
     * @var string
     */
    protected $clientSecret;

    /**
     * The client id to identify the password client in ther API
     * @var string
     */
    protected $passwordClientId;

    /**
     * The client secret to identify the password client in ther API
     * @var string
     */
    protected $passwordClientSecret;

  public function __construct()
  {
      $this->baseUri = config('services.market.base_uri');
      $this->clientId = config('services.market.client_id');
      $this->clientSecret = config('services.market.client_secret');
      $this->passwordClientId = config('services.market.password_client_id');
      $this->passwordClientSecret = config('services.market.password_client_secret');
  }

  /**
   * 与えられたコードからアクセストークンを取得する
   * @return stdClass
   */
  
  public function getCodeToken($code)
  {
      if ($token = $this->existingValidToken())  {
        return $token;
      }

      $formParams = [
          'grant_type' => 'authorization_code',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
          'redirect_uri' => route('authorization'),
          'code' => $code,
      ];

      $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

      $this->storeValidToken($tokenData, 'authorization_code');

      return $tokenData;
  }

  public function resolveAuthorizationUrl()
  {
      $query = http_build_query([
          'client_id' => $this->clientId,
          'redirect_uri' => route('authorization'),
          'response_type' => 'code',
          'scope' => 'purchase-product manage-products manage-account read-general',
      ]);

      return "{$this->basUri}/oauth/authze?{$query}";
  }

  public function storeValidToken($tokenData, $grantType)
  {
    $tokenData->token_expires_at = now()->addSeconds($tokenData->
      expire_in - 5);
    $tokenData->access_token = "{$tokenData->token_type} {$tokenData->
      access_token}";
    $tokenData->grant_type = $grantType;

    session()->put(['current_token' => $tokenData]);
  }

  public function existingValidToken()
  {
      if (session()->has('current_token')) {
          $tokenData = session()->get('current_token');

          if (now()->lt($tokenData->token_expires_at)) {
              return $tokenData->access_token;
          }
      }

      return false;
  }
}
