<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class LoginController extends BaseController{
  /**
   * Muestra la pantalla principal de inicio de sesion 
   * @link [https://www.google.com] Google
   * @return string
   */
  public function index(){
    return view('login');
  }
  /**
   * Muestra las opciones  de recuperación de acceso
   * @return string
   * @see \App\Models\Dashboard::index()
   */
  public function showRecovery(){
    return view('login');
  }
  public function showReset(){
    return view('login');
  }
  
}