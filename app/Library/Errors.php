<?php

namespace App\Library;

class Errors {
    public static function getErrors($error_ID) {
        $return = array("title" => "Error de base de datos.", "message" => "Existe un problema de base de datos, pongase en contacto con el administrador del sistema. Error No. " . $error_ID);
        switch($error_ID) {
            case '23000':
                $return = array("title" => "Error de registro duplicado.", "message"	=> "El registro o la clave que intenta ingresar ya se encuentra en la base de datos.");
            break;
        }

        return $return;
    }

    /* *********************************************************************
    * *************	ERRORES DE LA INTERFAZ DE ADMINISTRADOR ************
    * ****************************************************************** */

    //LOGIN
    const LOGIN_01_ID = "1_0001";
    const LOGIN_01_TITLE = "Acceso restringido.";
    const LOGIN_01_MESSAGE = "Correo o password incorrectos. Verifique su información.";

    //SESSIONS
    const SESION_01_ID = "2_0001";
    const SESION_01_TITLE = "La sesión ha expirado.";
    const SESION_01_MESSAGE = "La sesión del usuario ha finalizado, debe iniciar sesión nuevamente.";

    //OFFICES
    const OFFICE_CREATE_01_ID = "3_0001";
    const OFFICE_CREATE_01_TITLE = "Sucursal";
    const OFFICE_CREATE_01_MESSAGE = "Ocurrió un problema al crear la sucursal, vuelve a intentar más tarde.";

    //USERS
    const USER_CREATE_01_ID = "3_0001";
    const USER_CREATE_01_TITLE = "Usuario";
    const USER_CREATE_01_MESSAGE = "Ocurrió un problema al crear al usuario, vuelve a intentar más tarde.";

    const USER_EDIT_01_ID = "3_0002";
    const USER_EDIT_01_TITLE = "Usuario";
    const USER_EDIT_01_MESSAGE = "El usuario que intenta modificar no existe.";
    const USER_EDIT_02_ID = "3_0003";
    const USER_EDIT_02_TITLE = "Usuario";
    const USER_EDIT_02_MESSAGE = "Ocurrió un problema al modificar al usuario, vuelve a intentar más tarde.";

    //SPECIALTIES
    const SPECIALTY_CREATE_01_ID = "4_0001";
    const SPECIALTY_CREATE_01_TITLE = "Especialidad";
    const SPECIALTY_CREATE_01_MESSAGE = "Ocurrió un problema al crear la especialidad, vuelve a intentar más tarde.";

    const SPECIALTY_EDIT_01_ID = "4_0002";
    const SPECIALTY_EDIT_01_TITLE = "Especialidad";
    const SPECIALTY_EDIT_01_MESSAGE = "La especialidad que intenta modificar no existe.";
    const SPECIALTY_EDIT_02_ID = "4_0003";
    const SPECIALTY_EDIT_02_TITLE = "Especialidad";
    const SPECIALTY_EDIT_02_MESSAGE = "Ocurrió un problema al modificar la especialidad, vuelve a intentar más tarde.";

    const AD_CREATE_01_ID = "5_0001";
    const AD_CREATE_01_TITLE = "Anuncio";
    const AD_CREATE_01_MESSAGE = "Ocurrió un problema al crear el anuncio, vuelve a intentar más tarde.";
}
?>