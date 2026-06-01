<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Muestra la landing pública del negocio.
     *
     * Vista pública sin autenticación que presenta la tienda al público
     * general: hero, sección sobre nosotros, menú destacado, beneficios,
     * testimonios, ubicación y contacto.
     */
    public function index(): View
    {
        $products = Product::active()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('public.home', compact('products'));
    }
}
