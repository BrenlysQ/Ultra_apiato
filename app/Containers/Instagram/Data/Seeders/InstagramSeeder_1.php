<?php

namespace App\Containers\Instagram\Data\Seeders;

use App\Ship\Parents\Seeders\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
/**
 * Class InstagramSeeder_1
 *
 * @author  Enrique Villasana  <enrique.tecnologia@viajesplusultra.com>
 */
class InstagramSeeder_1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('insta_status')->insert([
            'title' => 'Not answered'
        ]);

        DB::table('insta_status')->insert([
            'title' => 'Answered'
        ]);
    }
}
