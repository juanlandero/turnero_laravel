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
            (4, 'Usuarios', 4, 'fa fa-user'),
            (5, 'Sucursales', 5, 'fa fa-globe')");

        //PRIVILEGES
        DB::statement("INSERT INTO
        privileges
        (
            id, privilege_category_id, privilege, description, menu, menu_order, menu_url, is_active
        )
        VALUES
            (1, 4, 'USERS', 'Lista de usuarios',          1, 1, 'users', 1),
            (2, 4, 'USERS_CREATE', 'Nuevo usuario',       1, 2, 'users/create', 1),
            (3, 4, 'USERS_EDIT', 'Editar usuario',        0, NULL, NULL, 1),
            (4, 4, 'USERS_DELETE', 'Borrar usuario',      0, NULL, NULL, 1),
            
            (5, 5, 'OFFICES', 'Lista de sucursales',      1, 1, 'offices', 1),
            (6, 5, 'OFFICES_CREATE', 'Nueva sucursal',    1, 2, 'offices/create', 1),
            (7, 5, 'OFFICES_EDIT', 'Editar sucursal',     0, NULL, NULL, 1),
            (8, 5, 'OFFICES_DELETE', 'Borrar sucursal',   0, NULL, NULL, 1);");

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
         (4, 8);");
    }
}
