<?php

namespace App\Containers\Instagram\Data\Seeders;

use App\Ship\Parents\Seeders\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
 * Class InstagramSeeder_2
 *
 * @author  Enrique Villasana  <enrique.tecnologia@viajesplusultra.com>
 */
class InstagramSeeder_2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('insta_default_messages')->insert([
            'title' => 'Message 1',
            'message' => 'Buenos Tardes! Nos puede indicar en qué lugar se encuentra? Su correo electrónico y número telefónico para comunicarnos con usted y enviarle la información solicitada.'
        ]);

        DB::table('insta_default_messages')->insert([
            'title' => 'Message 2',
            'message'=> 'Buenos Días! Nos puede indicar en qué lugar se encuentra? Su correo electrónico y número telefónico para comunicarnos con usted y enviarle la información solicitada.'
        ]);

        DB::table('insta_default_messages')->insert([
            'title' => 'Message 3',
            'message' => 'Para que fecha desea viajar?'
        ]);

        DB::table('insta_default_messages')->insert([
            'title' => 'Message 4',
            'message'=> 'Disculpe la tardanza! Nos estaremos comunicando con usted lo más pronto posible.'
        ]);

        DB::table('insta_default_messages')->insert([
            'title' => 'Message 5',
            'message'=> 'Gracias por la información! Uno de nuestros agentes se comunicará con usted.'
        ]);


    }
}
