<?php
namespace App\Models;
use CodeIgniter\Model;
/**
 * Esta clase opera directamente sobre los datos y funciones de los usuarios
 * @author Aimar Alexander Contreras Carrillo <1428345@senati.pe>
 * @version 1.0.0
 * @package Models
 */
class Login extends Model{
  /**
   * Nombre temporal del usuario anónimo
   * @var string
   */
  protected string $userTemp="Usuario";
  /**
   * Identificador del usuario que se identificó utilizando un servicio web
   * @var int
   */
  private int $idUserWeb=-1;
  /**
   * Asigna el código obtenido para el visitante web
   */
  public function __construct(){
    $this->idUserWeb=7;
  }
  /**
   * Método que permite validar la información del usuario y su contraseña
   * @param string $user  Nombre de Usuario que accede al sistema
   * @param string $password  Contraseña a validar
   * @return array{success: bool} El resultado  es un arreglo conteniendo información de la sesión
   */
  public function login($user,$password): array{
    return ["success"=>true];
  }
  /**
   * Reinicia la contraseña actual del usuario por el valor indicado
   * @param int $iduser clave primaria del usuario
   * @param string $password nueva contraseña
   * @return int  retorna 1 cuando el proceso se ejecuta correctamente
   */
  public function resetPassword(int $iduser,string $password): int{
    return 1;
  }
  /**
   * Cambia el estado del usuario a partir de un valor indicado
   * @param int $isuser Clave primaria de usuario
   * @param bool $status  Estado asignado al momento de la ejecución
   * @return bool Devuelve un valor lógico indicando el cambio
   */
  public function changeStatus(int $isuser, bool $status): bool{
    return true;
  }
  /**
   * Muestra inforación detallada del usuario, apellidos, nombres, nivel acceso y fecha activación
   * @param int $iduser  Clave primaria del usuario
   * @return string 
   */
  public function showUser(int $iduser): string{
      return "Jhon";
    
  }
}