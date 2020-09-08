<?php

namespace App\Containers\Hotusa\Data\Seeders;

use App\Ship\Parents\Seeders\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * Class HotusaSeeder_2
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class HotusaSeeder_2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Aire Acondicionado'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Calefaccion'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Amenities'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Cerradura Magnetica'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Minusvalidos'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Alergicos'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Perros'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Otros Animales'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'TV'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Pay TV'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Parabolica'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Canal Plus'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'TV Interactiva'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Video'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Telefono'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Telefono Baño'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Cunas'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Conectadas'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Otros'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Chimeneas'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Kitchenette'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Secador'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Minibar'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Hilo musical'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Radio'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Caja Fuerte'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Hi-fi'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Insonorizacion'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Conexion Fax'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Conexion P.C.'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Toma P.C.'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'R.D.S.I.'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Buzon de voz'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Presna Diaria'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Mesa de Trabajo'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Te y Cafe'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Voltaje'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Web T.V'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'MiniBar Gratuito'
        ]);

        DB::table('hotusa_servhab')->insert([
            'servhab_name' => 'Wifi'
        ]);
    }    
}
