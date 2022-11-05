<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request;

class WelcomeController extends Controller
{
  public function showWelcomePage()
  {
    $products = $this->marketService->getProducts();
    $categories = $this->marketService->getCategories();

    return view('welcome')
        ->with([
          'products' => $products,
          'categories' => $categories,
        ]);
  }
}

    public function getProduct($id)
    {
        return $this->makeRequest('GET', "products/{$id}");        
    }