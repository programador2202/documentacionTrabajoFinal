<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Usuario;
use App\Models\Personas;

class UsuarioController extends BaseController
{
public function index(): string
{
    $usuarioModel = new Usuario();
    $personaModel = new Personas();

    $datos['usuarios'] = $usuarioModel
        ->select('usuarios.*, personas.nombres, personas.apellidos')
        ->join('personas', 'personas.idpersona = usuarios.idpersona')
        ->orderBy('usuarios.idusuario', 'ASC')
        ->findAll();

    $datos['personas'] = $personaModel->findAll(); 
    $datos['header'] = view('admin/dashboard');

    return view('admin/recursos/Usuarios', $datos);
}



  
   public function ajax()
{
    $usuarioModel = new Usuario();
    $accion = $this->request->getVar('accion');
    $respuesta = ['status' => 'error', 'mensaje' => 'Acción no definida'];

   if ($accion === 'registrar') {
    $nivel = $this->request->getVar('nivelacceso');
    if (!in_array($nivel, ['admin', 'representante'])) $nivel = 'representante';

    $registro = [
        'nombreusuario' => $this->request->getVar('nombreusuario'),
        'claveacceso'   => password_hash($this->request->getVar('claveacceso'), PASSWORD_DEFAULT),
        'nivelacceso'   => $nivel,
        'idpersona'     => $this->request->getVar('idpersona')
    ];

    $usuarioModel->insert($registro);
    $respuesta = ['status' => 'success','mensaje'=>'Usuario registrado correctamente'];

} elseif ($accion === 'actualizar') {
    $id = $this->request->getVar('idusuario');
    $usuario = $usuarioModel->find($id);
    if (!$usuario) return $this->response->setJSON(['status'=>'error','mensaje'=>'Usuario no existe']);

    $nivel = $this->request->getVar('nivelacceso');
    if (!in_array($nivel, ['admin', 'representante'])) $nivel = $usuario['nivelacceso'];

    $datos = [
        'nombreusuario' => $this->request->getVar('nombreusuario'),
        'nivelacceso'   => $nivel,
        'idpersona'     => $this->request->getVar('idpersona')
    ];

    $clave = $this->request->getVar('claveacceso');
    if ($clave) $datos['claveacceso'] = password_hash($clave, PASSWORD_DEFAULT);

    $usuarioModel->update($id, $datos);
    $respuesta = ['status'=>'success','mensaje'=>'Usuario actualizado correctamente'];

    } elseif ($accion === 'borrar') {
        $id = $this->request->getVar('idusuario');
        $usuario = $usuarioModel->find($id);

        if ($usuario) {
            $usuarioModel->delete($id);
            $respuesta = ['status'=>'success','mensaje'=>'Usuario eliminado correctamente'];
        } else {
            $respuesta = ['status'=>'error','mensaje'=>'Usuario no existe'];
        }
    }

    return $this->response->setJSON($respuesta);
        }

    }


