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

    const SPECIALTY_DELETE_01_ID = "4_0004";
    const SPECIALTY_DELETE_01_TITLE = "Especialidad";
    const SPECIALTY_DELETE_01_MESSAGE = "La especialidad no se puede eliminar. Elimine primero la especialidad de sus asesores.";
    const SPECIALTY_DELETE_02_ID = "4_0004";
    const SPECIALTY_DELETE_02_TITLE = "Especialidad";
    const SPECIALTY_DELETE_02_MESSAGE = "Ocurrió un problema al eliminar la especialidad, vuelve a intentar más tarde.";

    //ADS
    const AD_CREATE_01_ID = "5_0001";
    const AD_CREATE_01_TITLE = "Anuncio";
    const AD_CREATE_01_MESSAGE = "Ocurrió un problema al crear el anuncio, vuelve a intentar más tarde.";
    const AD_CREATE_02_ID = "5_0002";
    const AD_CREATE_02_TITLE = "Anuncio";
    const AD_CREATE_02_MESSAGE = "El perfil administrador no tiene permitido crear registros de anuncios, solamente el supervisor.";
    
    const AD_DELETE_02_ID = "5_0003";
    const AD_DELETE_02_TITLE = "Anuncio";
    const AD_DELETE_02_MESSAGE = "Ocurrió un problema al eliminar el anuncio, vuelve a intentar más tarde.";

    //OFFICES
    const OFFICE_CREATE_01_ID = "6_0001";
    const OFFICE_CREATE_01_TITLE = "Sucursal";
    const OFFICE_CREATE_01_MESSAGE = "Ocurrió un problema al crear la sucursal, vuelve a intentar más tarde.";

    const OFFICE_EDIT_01_ID = "6_0002";
    const OFFICE_EDIT_01_TITLE = "Sucursal";
    const OFFICE_EDIT_01_MESSAGE = "La sucursal que intenta modificar no existe.";
    const OFFICE_EDIT_02_ID = "6_0003";
    const OFFICE_EDIT_02_TITLE = "Sucursal";
    const OFFICE_EDIT_02_MESSAGE = "Ocurrió un problema al modificar la sucursal, vuelve a intentar más tarde.";
    
    const OFFICE_DELETE_03_ID = "6_0004";
    const OFFICE_DELETE_03_TITLE = "Sucursal";
    const OFFICE_DELETE_03_MESSAGE = "Ocurrió un problema al eliminar la sucursal, vuelve a intentar más tarde.";

    //BOXES
    const BOX_CREATE_01_ID = "7_0001";
    const BOX_CREATE_01_TITLE = "Caja";
    const BOX_CREATE_01_MESSAGE = "Ocurrió un problema al crear la caja, vuelve a intentar más tarde.";

    const BOX_EDIT_01_ID = "7_0002";
    const BOX_EDIT_01_TITLE = "Caja";
    const BOX_EDIT_01_MESSAGE = "La caja que intenta modificar no existe.";
    const BOX_EDIT_02_ID = "7_0003";
    const BOX_EDIT_02_TITLE = "Caja";
    const BOX_EDIT_02_MESSAGE = "Ocurrió un problema al modificar la caja, vuelve a intentar más tarde.";

    //CLIENTS
    const CLIENT_CREATE_01_ID = "8_0001";
    const CLIENT_CREATE_01_TITLE = "Cliente";
    const CLIENT_CREATE_01_MESSAGE = "Ocurrió un problema al registrar al cliente, vuelve a intentar más tarde.";

    const CLIENT_EDIT_01_ID = "8_0002";
    const CLIENT_EDIT_01_TITLE = "Cliente";
    const CLIENT_EDIT_01_MESSAGE = "El cliente que intenta modificar no existe.";
    const CLIENT_EDIT_02_ID = "8_0003";
    const CLIENT_EDIT_02_TITLE = "Cliente";
    const CLIENT_EDIT_02_MESSAGE = "Ocurrió un problema al editar al cliente, vuelve a intentar más tarde.";
    
    const CLIENT_DELETE_03_ID = "8_0004";
    const CLIENT_DELETE_03_TITLE = "Cliente";
    const CLIENT_DELETE_03_MESSAGE = "Ocurrió un problema al eliminar al cliente, vuelve a intentar más tarde.";

    //USER-ADVISER
    const USER_ADVISER_DELETE_03_ID = "9_0004";
    const USER_ADVISER_03_TITLE = "Asesor";
    const USER_ADVISER_03_MESSAGE = "Ocurrió un problema al eliminar al asesor, vuelve a intentar más tarde.";

    //USER-SUPERVISOR
    const USER_SUPERVISOR_DELETE_03_ID = "10_0004";
    const USER_SUPERVISOR_03_TITLE = "Supervisor";
    const USER_SUPERVISOR_03_MESSAGE = "Ocurrió un problema al eliminar al supervisor, vuelve a intentar más tarde.";

    //USER-ADMIN
    const USER_ADMIN_DELETE_03_ID = "11_0004";
    const USER_ADMIN_03_TITLE = "Supervisor";
    const USER_ADMIN_03_MESSAGE = "Ocurrió un problema al eliminar al administrador, vuelve a intentar más tarde.";
}
?>