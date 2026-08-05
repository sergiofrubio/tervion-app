<?php
namespace App\Controllers;

use App\Core\Controller;

class LandingController extends Controller
{
    /**
     * Muestra la página de inicio (landing page).
     *
     * @return void
     */
    public function index()
    {
        $this->view('landing/index');
    }
}
