<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return redirect(uiRoute('gerenciar-demandas')); // redirecionar para a SPA, rota gerenciar-demandas, que é a "home" logada
    }
}
