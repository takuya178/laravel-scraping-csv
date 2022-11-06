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

  public function getClientCredentialsToken()
  {
      $formParams = [
          'grant_type' => 'client_credentials',
          'client_id' => $this->clientId,
          'client_secret' => $this->clientSecret,
      ];

      $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

      return "{$tokenData->token_type}" {"$tokenData->access_token"};
  }
}
