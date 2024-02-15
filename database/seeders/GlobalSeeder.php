<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bonus')->insert([
                ['name' => 'NCDOR','type'=>'D'],
                ['name' => 'Night hours','type'=>'D'],
                ['name' => 'Overtime Hours','type'=>'D'],
            ]
        );
        DB::table('detail_bonus')->insert([
                ['id_bonus' => 1, 'calc'=>2,'amount'=>'4'],
                ['id_bonus' => 2, 'calc'=>2,'amount'=>'25'],
                ['id_bonus' => 3, 'calc'=>2,'amount'=>'50'],
            ]
        );
    }
}
