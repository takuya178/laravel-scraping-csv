<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request;

class WelcomeController extends Controller
{
  public function showWelcomePage()
  {
    $products = $this->marketService->getProducts();

    return view('welcome')
        ->with([
          'products' => $products,
        ]);
  }
}
