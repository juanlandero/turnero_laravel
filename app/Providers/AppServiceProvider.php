<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->share('_PAGE_TITLE', 'Madero Refacciones');
        view()->share('_SYSTEM_NAME', 'Madero Refacciones Dashboard'); 
        View()->share('_SYSTEM_FULL_NAME', 'Sistema de Administración de Turnos');
        View()->share('_SYSTEM_DESCRIPTION', 'La forma fácil de administrar los turnos de tu sucursal.');
        View()->share('_SYSTEM_VERSION', 'v1.0');

        $colors = array(
            'green'     => array(   'hexadecimal'   => '#40c64b',
                                    'rgb'           =>  array( 'r' => 64, 'g' => 198, 'b' => 75)),
            'blue'      => array(   'hexadecimal'   => '#2c73ff',
                                    'rgb'           =>  array( 'r' => 44, 'g' => 115, 'b' => 255)),
            'yellow'    => array(   'hexadecimal'   => '#eabc00',
                                    'rgb'           =>  array( 'r' => 234, 'g' => 188, 'b' => 0)),
            'orange'    => array(   'hexadecimal'   => '#da6b00',
                                    'rgb'           =>  array( 'r' => 218, 'g' => 107, 'b' => 0)),
            'red'       => array(   'hexadecimal'   => '#d93826',
                                    'rgb'           =>  array( 'r' => 217, 'g' => 56, 'b' => 38)),
            'gray'      => array(   'hexadecimal'   => '#5c5c5c',
                                    'rgb'           =>  array( 'r' => 51, 'g' => 51, 'b' => 51)),
            'grey'      => array(   'hexadecimal'   => '#cccccc',
                                    'rgb'           =>  array( 'r' => 204, 'g' => 204, 'b' => 204)),
            'white'     => array(   'hexadecimal'   => '#ffffff',
                                    'rgb'           =>  array( 'r' => 255, 'g' => 255, 'b' => 255))
        );

        View()->share('_COLORS', $colors);

        Carbon::setLocale(config('app.locale'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
