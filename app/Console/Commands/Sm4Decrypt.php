<?php

namespace App\Console\Commands;

use App\Http\Common\Constant;
use Illuminate\Console\Command;

/**
 * 文件解密
 * 命令：php artisan sm4decrypt '待解密源文件绝对路径' '解密后保存的文件路径'。如：php artisan sm4decrypt 'E:\1\1a.docx' 'E:\1\1aa.docx'
 */
class Sm4Decrypt extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sm4decrypt {encrypt_file : 待解密源文件} {decrypt_file : 解密文件}';

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
     * D:/phpstudy_pro/Extensions/php/php8.0.2nts/php D:/phpstudy_pro/WWW/laravel9.52.15/artisan sm4decrypt '待解密文件绝对路径' '解密后文件保存路径(包含文件名)'
     * 如：D:/phpstudy_pro/Extensions/php/php8.0.2nts/php D:/phpstudy_pro/WWW/laravel9.52.15/artisan sm4decrypt 'D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/yuan.txt' 'D:/phpstudy_pro/WWW/laravel9.52.15/storage/app/public/jiemi_success.txt'
     *
     * @return int
     */
    public function handle() {
        $startTime = time();

        $encryptFile = $this->argument('encrypt_file');
        $decryptFile = $this->argument('decrypt_file');

        if (!file_exists($encryptFile)) {
            $msg = sprintf('待解密文件不存在：%s', $encryptFile);
            // cmslog(0, ['msg' => $msg]);
            return false;
        }

        // 读取要处理的文件内容
        $data = file_get_contents($encryptFile);

        // sm4-cfb,sm4-ctr,sm4-ofb 的使用方法一样
        $originalPlaintext = openssl_decrypt($data, "sm4-cfb", Constant::SM4_KEY, OPENSSL_RAW_DATA, Constant::SM4_IV);

        // 将加密后的内容保存到新文件
        file_put_contents($decryptFile, $originalPlaintext);

        $msg = sprintf('解密成功：%s -> %s ', $encryptFile, $decryptFile);

        $endTime = time();
        $exeTime = sprintf('解密执行时间：%s', $endTime - $startTime);
        // cmslog(1, ['msg' => $msg .$exeTime]);

        echo $msg .$exeTime;
        exit;
    }
}
