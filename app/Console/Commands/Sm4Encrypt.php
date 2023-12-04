<?php

namespace App\Console\Commands;

use App\Http\Common\Constant;
use Illuminate\Console\Command;

/**
 * 文件加密
 * 命令：php artisan sm4encrypt '待加密源文件绝对路径' '生成的加密文件保存路径'。如：php artisan sm4encrypt 'E:\1\1.docx' 'E:\1\1a.docx'
 */
class Sm4Encrypt extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm4encrypt {source_file : 待加密源文件} {encrypt_file : 加密文件}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * 控制台使用命令
     *
     * D:/phpstudy_pro/Extensions/php/php8.0.2nts/php D:/phpstudy_pro/WWW/laravel9.52.15/artisan sm4encrypt '待加密文件绝对路径' '加密后文件保存路径(包含文件名)'
     * 如：D:/phpstudy_pro/Extensions/php/php8.0.2nts/php D:/phpstudy_pro/WWW/laravel9.52.15/artisan sm4encrypt 'D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/yuan.txt' 'D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/jiami_success.txt'
     *
     * @return int
     */
    public function handle() {
        $startTime = time();

        $sourceFile = $this->argument('source_file');
        $encryptFile = $this->argument('encrypt_file');

        if (!file_exists($sourceFile)) {
            $msg = sprintf('待加密文件不存在：%s', $sourceFile);
            // cmslog(0, ['msg' => $msg]);

            echo $msg;
            return false;
        }

        // 读取要处理的文件内容
        $data = file_get_contents($sourceFile);

        // sm4-cfb,sm4-ctr,sm4-ofb 的使用方法一样
        $ciphertext = openssl_encrypt($data, "sm4-cfb", Constant::SM4_KEY, OPENSSL_RAW_DATA, Constant::SM4_IV);

        // 将加密后的内容保存到新文件
        file_put_contents($encryptFile, $ciphertext);

        $msg = sprintf('加密成功：%s -> %s ', $sourceFile, $encryptFile);

        $endTime = time();
        $exeTime = sprintf('加密执行时间：%s', $endTime - $startTime);
        // cmslog(1, ['msg' => $msg .$exeTime]);

        echo $msg .$exeTime;
        exit;
    }
}
