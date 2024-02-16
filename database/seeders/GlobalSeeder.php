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
                ['name' => 'NCDOR','type'=>'D','permanent'=>true],
                ['name' => 'Night hours','type'=>'D','permanent'=>true],
                ['name' => 'Overtime Hours','type'=>'D','permanent'=>true],
                ['name' => 'Overtime night','type'=>'D','permanent'=>true],

            ]
        );
        DB::table('detail_bonus')->insert([
                ['id_bonus' => 1, 'calc'=>2,'amount'=>'4'],
                ['id_bonus' => 2, 'calc'=>2,'amount'=>'25'],
                ['id_bonus' => 3, 'calc'=>2,'amount'=>'50'],
            ]
        );

        DB::table('payroll')->insert([
                [ 'start'=>'01/01/2000','end'=>'15/01/2000','type'=>'D','users_id'=>1,'description'=>"Descripcion de planilla"],
                [ 'start'=>'16/01/2000','end'=>'31/01/2000','type'=>'D','users_id'=>1,'description'=>"Descripcion de planilla"],
            ]
        );

        DB::table('worker')->insert([
                [ 'date_in'=>'01/01/2000','date_out'=>'15/01/2000','birthdate'=>'15/01/2000','name'=>'Roberto','last_name'=>'Carlos','salary'=>10,'social_number'=>38437434873483
                    , 'rate_night'=>15,'email'=>"correo@gmail.com","address"=>"Direccion",'contact'=>'Contacto'],
                [ 'date_in'=>'01/01/2000','date_out'=>'15/01/2000','birthdate'=>'15/01/2000','name'=>'Marco','last_name'=>'Polo','salary'=>10,'social_number'=>38437434873483
                    , 'rate_night'=>15,'email'=>"correo2@gmail.com","address"=>"Direccion",'contact'=>'Contacto']
            ]
        );
    }
}
