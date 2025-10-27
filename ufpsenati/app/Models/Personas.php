<?php

namespace App\Models;

use CodeIgniter\Model;

class Personas extends Model
{
    protected $table      = 'personas';
    protected $primaryKey = 'idpersona';

    // Campos permitidos para insert/update
    protected $allowedFields = [
        'apellidos',
        'nombres',
        'tipodoc',
        'numerodoc',
        'telefono',
        'foto'
    ];

    
}
