<?php
// 実装したTraitを使う
// ConsumesExternalで定義したresolveAuthorization, decodeResponse, checkIfErrorResponseを定義


namespace App\Services;

use App\Traits\AuthorizesmarketRequests;
use App\Traits\ConsumesExternalServices;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDeprecationHandling;

class MarketService
{
  use ConsumesExternalServices, AuthorizesmarketRequests,
      InteractsWithDeprecationHandling;

  protected $baseUri;

  public function __construct()
  {
    $this->baseUri = config('services.market.base_uri');
  }
}
