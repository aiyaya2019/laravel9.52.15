<?php

namespace App\Http\Controllers\Open;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class TestController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function t1() {
        $jiami = sm2Encrypt('qwe123qwe');
        $jie = sm2Decrypt($jiami);

        echo '加密后：' .$jiami ."<br/>";
        echo '解密后：' .$jie ."<br/>";
        exit;
    }



}
