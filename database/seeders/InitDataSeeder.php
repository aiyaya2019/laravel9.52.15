<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkSql = "SELECT * FROM migrations where migration = 'InitDataSeeder62'";
        $check = DB::select($checkSql);

        if (empty($check)) {
            $time = date('Y-m-d H:i:s');

            DB::table('admin')->insert([
                [
                    'name' => '孙悟空',
                    'account' => 'admin',
                    'password' => Hash::make('admin'),
                    'updated_at' => $time,
                ],
            ]);

            // 预防重复填充数据
            DB::table('migrations')->insert([
                'migration' => 'InitDataSeeder',
                'batch' => 1,
            ]);
        }
    }
}
