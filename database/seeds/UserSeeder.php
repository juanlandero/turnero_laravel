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
        $objUser->first_name    = "Centro";
        $objUser->second_name   = ".";
        $objUser->user_type_id  = 1;
        $objUser->email         = "admin@madero.com";
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
            (1, 'Turnos', 1, 'fa fa-ticket-alt'),
            (2, 'Reportes', 2, 'fa fa-chart-line'),
            (3, 'Especialidades', 3, 'fa fa-rocket'),
            (4, 'Administradores', 4, 'fa fa-user-tie'),
            (5, 'Supervisores', 5, 'fa fa-user-check'),
            (6, 'Asesores', 6, 'fa fa-user'),
            (7, 'Sucursales', 7, 'fas fa-map-marker-alt'),
            (8, 'Anuncios', 8, 'fas fa-photo-video'),
            (9, 'Cajas', 9, 'fas fa-cash-register'),
            (10, 'Caja', 10, 'fas fa-fire');");

        //PRIVILEGES
        DB::statement("INSERT INTO
        privileges
        (
            id, privilege_category_id, privilege, description, menu, menu_order, menu_url, is_active
        )
        VALUES
            (1, 1, 'SHIFTS',            'Panel Turnos',     1, 1, 'shifts', 1),
            (2, 1, 'SHIFTS_REASSIGNED', 'Reasignar turno',  1, 2, 'shifts/reassigned', 1),

            (3, 2, 'REPORTS',          'Lista de reportes',    1, 1, 'reports', 1), 
            (4, 2, 'REPORTS_GENERAL',  'Reporte general',      0, NULL, NULL, 1),
            (5, 2, 'REPORTS_SHIFT',    'Reporte de turnos',    0, NULL, NULL, 1),
            (6, 2, 'REPORTS_ADVISOR',  'Reporte de asesores',  0, NULL, NULL, 1),

            (7, 3, 'SPECIALTIES', 'Lista de especialidades',           1, 1, 'specialties', 1),
            (8, 3, 'SPECIALTIES_CREATE', 'Nueva especialidad',         1, 2, 'specialties/create', 1),
            (9, 3, 'SPECIALTIES_EDIT', 'Editar especialidad',          0, NULL, NULL, 1),
            (10, 3, 'SPECIALTIES_DELETE', 'Borrar especialidad',        0, NULL, NULL, 1),

            (11, 4, 'USERS_ADMINS', 'Lista de administradores',         1, 1, 'users-admins', 1),
            (12, 4, 'USERS_ADMINS_CREATE', 'Nuevo administrador',       1, 2, 'users-admins/create', 1),
            (13, 4, 'USERS_ADMINS_EDIT', 'Editar administrador',        0, NULL, NULL, 1),
            (14, 4, 'USERS_ADMINS_DELETE', 'Borrar administrador',      0, NULL, NULL, 1),

            (15, 5, 'USERS_SUPERVISORS', 'Lista de supervisores',         1, 1, 'users-supervisors', 1),
            (16, 5, 'USERS_SUPERVISORS_CREATE', 'Nuevo supervisor',       1, 2, 'users-supervisors/create', 1),
            (17, 5, 'USERS_SUPERVISORS_EDIT', 'Editar supervisor',        0, NULL, NULL, 1),
            (18, 5, 'USERS_SUPERVISORS_DELETE', 'Borrar supervisor',      0, NULL, NULL, 1),

            (19, 6, 'USERS_ADVISERS', 'Lista de asesores',               1, 1, 'users-advisers', 1),
            (20, 6, 'USERS_ADVISERS_CREATE', 'Nuevo asesor',            1, 2, 'users-advisers/create', 1),
            (21, 6, 'USERS_ADVISERS_EDIT', 'Editar asesor',             0, NULL, NULL, 1),
            (22, 6, 'USERS_ADVISERS_DELETE', 'Borrar asesor',           0, NULL, NULL, 1),
            
            (23, 7, 'OFFICES', 'Lista de sucursales',                   1, 1, 'offices', 1),
            (24, 7, 'OFFICES_CREATE', 'Nueva sucursal',                 1, 2, 'offices/create', 1),
            (25, 7, 'OFFICES_EDIT', 'Editar sucursal',                  0, NULL, NULL, 1),
            (26, 7, 'OFFICES_DELETE', 'Borrar sucursal',                0, NULL, NULL, 1),

            (27, 8, 'CAROUSEL', 'Lista de Anuncios', 1, 1, 'ads', 1), 
            (28, 8, 'CAROUSEL_CREATE', 'Nuevo Anuncio', 1, 2, 'ads/create', 1),
            
            (29, 9, 'BOXES', 'Lista de cajas',                    1, 1, 'boxes', 1),
            (30, 9, 'BOXES_CREATE', 'Nueva caja',                 1, 2, 'boxes/create', 1),
            (31, 9, 'BOXES_EDIT', 'Editar caja',                  0, NULL, NULL, 1),
            (32, 9, 'BOXES_DELETE', 'Borrar caja',                0, NULL, NULL, 1),
            
            (33, 10, 'CLIENTS', 'Lista de clientes',                    1, 1, 'clients', 1),
            (34, 10, 'CLIENTS_CREATE', 'Nuevo cliente',                 1, 2, 'clients/create', 1),
            (35, 10, 'CLIENTS_EDIT', 'Editar cliente',                  0, NULL, NULL, 1),
            (36, 10, 'CLIENTS_DELETE', 'Borrar cliente',                0, NULL, NULL, 1);");
           

        // USER PRIVILEGES
        DB::statement("INSERT INTO
            users_privileges
        (
            user_id, privilege_id
        )
        VALUES
            (1, 7),
            (1, 8),
            (1, 9),
            (1, 10),
            (1, 11),
            (1, 12),
            (1, 13),
            (1, 14),
            (1, 15),
            (1, 16),
            (1, 17),
            (1, 18),
            (1, 19),
            (1, 20),
            (1, 21),
            (1, 22),
            (1, 23),
            (1, 24),
            (1, 25),
            (1, 26),
            (1, 29),
            (1, 30),
            (1, 31),
            (1, 32);");

        // USER OFFICES
        // DB::statement("INSERT INTO  user_offices
        // (
        //     id, user_id, office_id, box_id, created_at, updated_at
        // )
        // VALUES
        //     ( 1, 1, 1, 1, 1, true, NOW(), NOW()),
        //     ( 2, 2, 1, 2, 1, true, NOW(), NOW()),
        //     ( 3, 3, 1, 3, 1, true, NOW(), NOW()),
        //     ( 4, 4, 1, 3, 1, true, NOW(), NOW());
        // ");

         // SPECIALITY TYPE USER
        // DB::statement("INSERT INTO  speciality_type_users
        // (
        //     id, speciality_type_id, user_id, created_at, updated_at
        // )
        // VALUES
        //     ( 1, 5, 1, true, NOW(), NOW()),
        //     ( 2, 5, 2, true, NOW(), NOW()),
        //     ( 3, 2, 3, true, NOW(), NOW()),
        //     ( 4, 2, 1, true, NOW(), NOW()),
        //     ( 5, 1, 1, true, NOW(), NOW()),
        //     ( 6, 3, 3, true, NOW(), NOW()),
        //     ( 7, 5, 4, true, NOW(), NOW()),
        //     ( 8, 4, 4, true, NOW(), NOW());
        // ");
    }
}
