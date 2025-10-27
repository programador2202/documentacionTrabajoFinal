<?php

namespace App\Models;

use CodeIgniter\Model;

class Reservas extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'idreserva';

    protected $allowedFields = [
        'idhorario',
        'idlocales',
        'fechahora',
        'cantidadpersonas',
        'confirmacion',
        'idusuariovalida',
        'idpersonasolicitud'
    ];

    protected $useTimestamps = false;

    /**
     * Obtiene todas las reservas con información relacionada
     */
public function obtenerReservasConUsuarios()
{
    return $this->select("
            reservas.*, 
            CONCAT(ul.nombre, ' ', ul.apellido) AS solicitante,
            ul.telefono AS telefono_solicitante,
            CONCAT(p.nombres, ' ', p.apellidos) AS validador,
            CONCAT(h.inicio, ' - ', h.fin) AS horario,
            n.nombre AS nombre_local
        ")
        ->join('usuarios_login ul', 'ul.id = reservas.idpersonasolicitud')
        ->join('usuarios u', 'u.idusuario = reservas.idusuariovalida', 'left')
        ->join('personas p', 'p.idpersona = u.idpersona', 'left')
        ->join('horarios h', 'h.idhorario = reservas.idhorario')
        ->join('locales l', 'l.idlocales = reservas.idlocales')
        ->join('negocios n', 'n.idnegocio = l.idnegocio')
        ->orderBy('reservas.idreserva', 'DESC')
        ->findAll();
}
    

public function obtenerReservasPorRepresentante($idpersona)
{
    return $this->select("
            reservas.*, 
            CONCAT(ul.nombre, ' ', ul.apellido) AS solicitante,
            CONCAT(p.nombres, ' ', p.apellidos) AS validador,
            CONCAT(h.inicio, ' - ', h.fin) AS horario,
            l.idlocales,
            l.idnegocio
        ")
        ->join('usuarios_login ul', 'ul.id = reservas.idpersonasolicitud')
        ->join('usuarios u', 'u.idusuario = reservas.idusuariovalida', 'left')
        ->join('personas p', 'p.idpersona = u.idpersona', 'left')
        ->join('horarios h', 'h.idhorario = reservas.idhorario')
        ->join('locales l', 'l.idlocales = reservas.idlocales')
        ->join('negocios n', 'n.idnegocio = l.idnegocio')
        ->where('n.idrepresentante', $idpersona)
        ->orderBy('reservas.idreserva', 'DESC')
        ->findAll();
}


}