<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
                ['id_bonus' => 1, 'calc'=>2,'amount'=>'4','date'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')],
                ['id_bonus' => 2, 'calc'=>2,'amount'=>'25','date'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')],
                ['id_bonus' => 3, 'calc'=>2,'amount'=>'50','date'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')],
                ['id_bonus' => 4, 'calc'=>2,'amount'=>'50','date'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')],

            ]
        );
        DB::table('config')->insert([
                ['id'=>1, 'title_videos'=>'Important Links for Your Review' , 'videos' => 'I hope this message finds you well. I am writing to share some important links that I believe will be beneficial for your current projects and ongoing professional development. Please take a moment to review the following resources:',
                  'title_pay'=>'Details of Your Pay Statement' ,
                'pay'=>"I hope this message finds you well. I am reaching out to provide you with detailed information regarding the deductions, bonuses, and the total amount received in your recent pay statement."
                ],
            ]
        );
//
//        DB::table('link')->insert([
//                ['name' => "https://www.youtube.com/watch?v=JxS5E-kZc2s"],
//                ['name' => "https://www.youtube.com/watch?v=u0XHVssfsYM"],
//            ]
//        );
//
//
//        DB::table('bonus')->insert([
//                ['name' => 'Descuento','type'=>'D','permanent'=>false],
//                ['name' => 'Bonificacion','type'=>'B','permanent'=>false],
//            ]
//        );
//
//
//        DB::table('detail_bonus')->insert([
//                ['id_bonus' => 5, 'calc'=>1,'amount'=>'100','date'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')],
//                ['id_bonus' => 6, 'calc'=>1,'amount'=>'50','date'=>Carbon::createFromFormat('Y-m-d', '2023-01-15')],
//
//            ]
//        );
//
//        DB::table('bonus_payroll')->insert([
//                ['id_detail_bonus' => 5, 'id_payroll'=>1,'id_worker'=>1],
//                ['id_detail_bonus' => 6, 'id_payroll'=>1,'id_worker'=>1],
//
//            ]
//        );
//        DB::table('payroll')->insert([
//                [ 'start'=>  Carbon::createFromFormat('Y-m-d', '2024-01-01') ,'end'=> Carbon::createFromFormat('Y-m-d', '2023-01-15') ,'type'=>'D','description'=>"Descripcion de planilla dia"],
//                [ 'start'=>  Carbon::createFromFormat('Y-m-d', '2024-01-01') ,'end'=> Carbon::createFromFormat('Y-m-d', '2024-01-15') ,'type'=>'D','description'=>"Descripcion de planilla dia"],
//                [ 'start'=> Carbon::createFromFormat('Y-m-d', '2024-01-16'),'end'=>Carbon::createFromFormat('Y-m-d', '2024-01-31'),'type'=>'N','description'=>"Descripcion de planilla noche"],
//                [ 'start'=> Carbon::createFromFormat('Y-m-d', '2023-01-16'),'end'=>Carbon::createFromFormat('Y-m-d', '2023-01-31'),'type'=>'N','description'=>"Descripcion de planilla noche"],
//
//            ]
//        );
//        $global = DB::table('bonus')->join('detail_bonus', 'detail_bonus.id_bonus', '=', 'bonus.id')->where('permanent', '=', true)
//            ->where('active', '=', 1)->select('detail_bonus.id as id')->get();
//        foreach ($global as $item){
//            DB::table('bonus_payroll')->insert(['id_detail_bonus' => $item->id, 'id_payroll'=>1,'id_worker'=>null]);
//            DB::table('bonus_payroll')->insert(['id_detail_bonus' => $item->id, 'id_payroll'=>2,'id_worker'=>null]);
//            DB::table('bonus_payroll')->insert(['id_detail_bonus' => $item->id, 'id_payroll'=>3,'id_worker'=>null]);
//            DB::table('bonus_payroll')->insert(['id_detail_bonus' => $item->id, 'id_payroll'=>4,'id_worker'=>null]);
//        }
//
//        DB::table('worker')->insert([
//                [ 'date_in'=>'01/01/2000','date_out'=>'15/01/2000','birthdate'=>'15/01/2000','name'=>'Roberto','last_name'=>'Carlos','salary'=>10,'social_number'=>'38437434873483'
//                    , 'rate_night'=>15,'email'=>"efraindeleon12@outlook.com","address"=>"Direccion",'contact'=>'Contacto','cel'=>'565633543'],
//                [ 'date_in'=>'01/01/2000','date_out'=>'15/01/2000','birthdate'=>'15/01/2000','name'=>'Marco','last_name'=>'Polo','salary'=>10,'social_number'=>'38437434873483'
//                    , 'rate_night'=>15,'email'=>"xlsymphonylx@gmail.com","address"=>"Direccion",'contact'=>'Contacto','cel'=>'565633543']
//            ]
//        );
//
//            DB::table('report')->insert([
//                    [ 'regular'=>'8','extra'=>'4','night'=>'4','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>1,'id_worker'=>1],
//                    [ 'regular'=>'6','extra'=>'4','night'=>'4','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>1,'id_worker'=>1],
//                    [ 'regular'=>'2','extra'=>'4','night'=>'4','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>1,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'0','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>1,'id_worker'=>2],
//                    [ 'regular'=>'6','extra'=>'4','night'=>'0','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>1,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'0','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>1,'id_worker'=>2],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'2','overtime_night'=>'4','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>2,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'4','overtime_night'=>'1','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>2,'id_worker'=>2],
//                    [ 'regular'=>'6','extra'=>'4','night'=>'2','overtime_night'=>'3','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>2,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'3','overtime_night'=>'2','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>2,'id_worker'=>2],
//                    [ 'regular'=>'8','extra'=>'6','night'=>'4','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>3,'id_worker'=>1],
//                    [ 'regular'=>'6','extra'=>'4','night'=>'4','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>3,'id_worker'=>1],
//                    [ 'regular'=>'2','extra'=>'4','night'=>'4','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>3,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'6','night'=>'2','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>3,'id_worker'=>2],
//                    [ 'regular'=>'6','extra'=>'4','night'=>'1','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>3,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'7','night'=>'2','overtime_night'=>'0','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>3,'id_worker'=>2],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'3','overtime_night'=>'2','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>4,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'4','night'=>'4','overtime_night'=>'5','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>4,'id_worker'=>2],
//                    [ 'regular'=>'6','extra'=>'4','night'=>'2','overtime_night'=>'3','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>4,'id_worker'=>1],
//                    [ 'regular'=>'8','extra'=>'5','night'=>'3','overtime_night'=>'2','start'=>'15/01/2000','end'=>'15/01/2000','id_payroll'=>4,'id_worker'=>2],
//                ]
//            );
//
  }
}
