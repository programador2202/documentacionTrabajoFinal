<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Reservas;

class ReservasController extends BaseController
{
    public function index()
{
    $reservaModel = new Reservas();
    $nivel        = session()->get('nivelacceso');
    $idpersona    = session()->get('idpersona');

    if ($nivel === 'representante') {
        // Obtener reservas solo de los locales que pertenecen al representante
        $reservas = $reservaModel->obtenerReservasPorRepresentante($idpersona);
    } else {
        // Para admin u otros niveles, traer todas
        $reservas = $reservaModel->obtenerReservasConUsuarios();
    }

    $data = [
        'reservas' => $reservas,
        'header'   => view('admin/dashboard')
    ];

    return view('admin/recursos/Reservas', $data);
    
}

public function vistaPublica()
{
    $session = session();

    $data = [
        'nombreCompleto' => $session->has('logged_in')
            ? $session->get('nombre') . ' ' . $session->get('apellido')
            : '',
        'idusuario' => $session->get('user_id') ?? null,
        'email'     => $session->get('email') ?? '',
        'telefono'  => $session->get('telefono') ?? '',
    ];

    return view('PaginaPrincipal/Reservas', $data);
}




    // Método AJAX general

    public function ajax()
    {
        $reservaModel = new Reservas();
        $accion       = $this->request->getVar('accion');
        $respuesta    = ['status' => 'error', 'mensaje' => 'Acción no definida'];

        // Registrar reserva
        if ($accion === 'registrar') {
            $registro = [
                'idhorario'          => $this->request->getVar('idhorario'),
                'idlocales'          => $this->request->getVar('idlocales'),
                'fechahora'          => $this->request->getVar('fechahora'),
                'cantidadpersonas'   => $this->request->getVar('cantidadpersonas'),
                'confirmacion'       => 'pendiente',
                'idpersonasolicitud' => $this->request->getVar('idpersonasolicitud'),
            ];

            $reservaModel->insert($registro);
            $respuesta = ['status' => 'success', 'mensaje' => 'Reserva registrada correctamente'];
        }

        // Actualizar reserva
        elseif ($accion === 'actualizar') {
            $id = $this->request->getVar('idreserva');
            $reserva = $reservaModel->find($id);

            if (!$reserva) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'mensaje' => 'La reserva no existe'
                ]);
            }

            $datos = [
                'idhorario'        => $this->request->getVar('idhorario'),
                'idlocales'        => $this->request->getVar('idlocales'),
                'fechahora'        => $this->request->getVar('fechahora'),
                'cantidadpersonas' => $this->request->getVar('cantidadpersonas'),
                'confirmacion'     => $this->request->getVar('confirmacion'),
                'idusuariovalida'  => $this->request->getVar('idusuariovalida'),
            ];

            $reservaModel->update($id, $datos);
            $respuesta = ['status' => 'success', 'mensaje' => 'Reserva actualizada correctamente'];
        }

        // Eliminar reserva
        elseif ($accion === 'borrar') {
            $id = $this->request->getVar('idreserva');
            $reserva = $reservaModel->find($id);

            if ($reserva) {
                try {
                    $reservaModel->delete($id);
                    $respuesta = ['status' => 'success', 'mensaje' => 'Reserva eliminada correctamente'];
                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), '1451') !== false) {
                        $respuesta = [
                            'status'  => 'error',
                            'mensaje' => 'No se puede eliminar la reserva porque está relacionada con otros registros'
                        ];
                    } else {
                        $respuesta = [
                            'status'  => 'error',
                            'mensaje' => 'Error al intentar eliminar la reserva'
                        ];
                    }
                }
            } else {
                $respuesta = ['status' => 'error', 'mensaje' => 'La reserva no existe'];
            }
        }

        // Cambiar estado (confirmar o cancelar)
        elseif ($accion === 'cambiar_estado') {
            $id = $this->request->getVar('idreserva');
            $nuevoEstado = $this->request->getVar('estado');

            if (!in_array($nuevoEstado, ['pendiente', 'confirmado', 'cancelado'])) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'mensaje' => 'Estado no válido'
                ]);
            }

            $reservaModel->update($id, ['confirmacion' => $nuevoEstado]);
            $respuesta = [
                'status'  => 'success',
                'mensaje' => 'Estado de reserva actualizado a: ' . ucfirst($nuevoEstado)
            ];
        }

        return $this->response->setJSON($respuesta);
    }
}
