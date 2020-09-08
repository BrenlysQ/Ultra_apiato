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
class InstagramSeeder_3 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Valencia',
            'name' => 'Yonder Valencia',
            'email' => 'plusultravln4@gmail.com',
            'phone' => '0241000000000', 
            'address' => 'Valencia Hesperia'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Valencia',
            'name' => 'Grendy Valencia',
            'email' => 'plusultravln3@gmail.com',
            'phone' => '0241000000000', 
            'address' => 'Valencia Hesperia'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Valencia',
            'name' => 'Ayhary Valencia',
            'email' => 'plusultravln2@gmail.com',
            'phone' => '0241000000000', 
            'address' => 'Valencia Hesperia'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Caracas',
            'name' => 'Gerente Caracas',
            'email' => 'gerentesccs@gmail.com',
            'phone' => '0212000000000', 
            'address' => 'Caracas'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Maracaibo',
            'name' => 'Gerente Maracaibo',
            'email' => 'Gerente.maracaibo@gmail.com',
            'phone' => '0261000000000', 
            'address' => 'Caracas'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Maracay',
            'name' => 'Sede maracay',
            'email' => 'Plusultramaracay.ventas2@gmail.com',
            'phone' => '0261000000000',
            'address' => 'Caracas'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra gerente de Ventas',
            'name' => 'Gerente De Ventas - William',
            'email' => 'gventas@viajesplusultra.com',
            'phone' => '0241000000000',
            'address' => 'Valencia'
        ]);

        DB::table('insta_contacts')->insert([
            'title' => 'Plusultra Panama',
            'name' => 'Gerente De Panama',
            'email' => 'plusultrapanama05@gmail.com',
            'phone' => '507 000000000',
            'address' => 'Panama'
        ]);

    }
}
