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

    /**
     * Muestra la política de privacidad.
     */
    public function privacidad()
    {
        $this->view('landing/privacidad');
    }

    /**
     * Muestra los términos de servicio.
     */
    public function terminos()
    {
        $this->view('landing/terminos');
    }

    /**
     * Muestra la política de cookies.
     */
    public function cookies()
    {
        $this->view('landing/cookies');
    }
}
