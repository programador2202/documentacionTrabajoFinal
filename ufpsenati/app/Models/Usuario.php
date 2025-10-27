<?php

namespace App\Models;
use CodeIgniter\Model;

class Usuario extends Model{

protected $table = 'usuarios';
protected $primaryKey = "idusuario";
protected $allowedFields = ["nombreusuario","claveacceso","nivelacceso","idpersona"];


}