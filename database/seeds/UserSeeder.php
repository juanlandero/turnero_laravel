<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objUser                = new User();
        $objUser->name          = "Administrador";
        $objUser->first_name    = ".";
        $objUser->second_name   = ".";
        $objUser->user_type_id  = 1;
        $objUser->email         = "admin@dashboard.mx";
        $objUser->password      = bcrypt('A123456*');
        $objUser->is_active     = true;
        $objUser->save();

        //PRIVILEGES CATEGORIES
        DB::statement("INSERT INTO
            privileges_categories
        (
            id, privilege_category, menu_order, icon
        )
        VALUES
            (1, 'Tickets', 1, 'fa fa-ticket'),
            (2, 'Reportes', 2, 'fa fa-line-chart'),
            (3, 'Especialidades', 3, 'fa fa-handshake-o'),
            (4, 'Administradores', 4, 'fa fa-user'),
            (5, 'Supervisores', 5, 'fa fa-user'),
            (6, 'Asesores', 6, 'fa fa-user'),
            (7, 'Sucursales', 7, 'fa fa-globe')");

        //PRIVILEGES
        DB::statement("INSERT INTO
        privileges
        (
            id, privilege_category_id, privilege, description, menu, menu_order, menu_url, is_active
        )
        VALUES
            (1, 4, 'USERS_ADMINS', 'Lista de administradores',         1, 1, 'users-admins', 1),
            (2, 4, 'USERS_ADMINS_CREATE', 'Nuevo administrador',       1, 2, 'users-admins/create', 1),
            (3, 4, 'USERS_ADMINS_EDIT', 'Editar administrador',        0, NULL, NULL, 1),
            (4, 4, 'USERS_ADMINS_DELETE', 'Borrar administrador',      0, NULL, NULL, 1),

            (5, 5, 'USERS_SUPERVISORS', 'Lista de supervisores',         1, 1, 'users-supervisors', 1),
            (6, 5, 'USERS_SUPERVISORS_CREATE', 'Nuevo supervisor',       1, 2, 'users-supervisors/create', 1),
            (7, 5, 'USERS_SUPERVISORS_EDIT', 'Editar supervisor',        0, NULL, NULL, 1),
            (8, 5, 'USERS_SUPERVISORS_DELETE', 'Borrar supervisor',      0, NULL, NULL, 1),

            (9, 6, 'USERS_ADVISERS', 'Lista de asesores',          1, 1, 'users-advisers', 1),
            (10, 6, 'USERS_ADVISERS_CREATE', 'Nuevo asesor',       1, 2, 'users-advisers/create', 1),
            (11, 6, 'USERS_ADVISERS_EDIT', 'Editar asesor',        0, NULL, NULL, 1),
            (12, 6, 'USERS_ADVISERS_DELETE', 'Borrar asesor',      0, NULL, NULL, 1),
            
            (13, 7, 'OFFICES', 'Lista de sucursales',      1, 1, 'offices', 1),
            (14, 7, 'OFFICES_CREATE', 'Nueva sucursal',    1, 2, 'offices/create', 1),
            (15, 7, 'OFFICES_EDIT', 'Editar sucursal',     0, NULL, NULL, 1),
            (16, 7, 'OFFICES_DELETE', 'Borrar sucursal',   0, NULL, NULL, 1);");

        // USER PRIVILEGES
        DB::statement("INSERT INTO
            users_privileges
        (
            user_id, privilege_id
        )
        VALUES
         (4, 1),
         (4, 2),
         (4, 3),
         (4, 4),
         (4, 5),
         (4, 6),
         (4, 7),
         (4, 8),
         (4, 9),
         (4, 10),
         (4, 11),
         (4, 12),
         (4, 13),
         (4, 14),
         (4, 15),
         (4, 16);");
    }
}
