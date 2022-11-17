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

  public function getProducts()
  {
      return $this->makeRequest('GET', 'products');
  }

  public function getProduct($id)
  {
      return $this->makeRequest('GET', "products/{$id}");
  }

  public function getCategories()
  {
      return $this->makeRequest('GET', 'categories');
  }

  public function getCategoryProducts($id)
  {
      return $this->makeRequest('GET', "categories/{$id}/products");
  }

  public function getUserInformation()
  {
      return $this->makeRequest('GET', 'users/me');
  }
}
