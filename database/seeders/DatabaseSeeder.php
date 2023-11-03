<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 运行php artisan migrate之前，先关闭laravel mysql数据库严格模式： 将config/database.php中配置'strict' => true 改为 'strict' => false
 *
 * 1、创建新迁移文件命令：php artisan make:migration 文件名。如：php artisan make:migration create_test_table。注意：如手动添加文件，需注意文件名的时间先后
 * 2、迁移数据库结构命令：php artisan migrate
 * 3、填充初始数据命令: php artisan db:seed --class=DatabaseSeeder（新建的填充类需要追加到DatabaseSeeder.php中的$this->call()里面的数组尾部，这样可以指定执行DatabaseSeeder时也可以执行里面的填充类）
 * 4、迁移回滚命令：php artisan migrate:rollback
 * 5、回滚并重新运行迁移命令(就是删除所有表再重新生成，数据填充不会执行)：php artisan migrate:refresh --seed
 *
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 新填充类从数组后面追加
        $this->call([
            InitDataSeeder::class,
        ]);
    }
}
