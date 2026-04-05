<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Retorna el usuario autenticado con tipo correcto.
     */
    protected function authUser(): \App\Models\User
    {
        return \Illuminate\Support\Facades\Auth::user();
    }
}