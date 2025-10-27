<?php 
namespace App\Controllers;
use App\Controllers\BaseController;

class Dashboardcontroller extends BaseController{

  public function index(){
    return view('dashboard');
  }
}