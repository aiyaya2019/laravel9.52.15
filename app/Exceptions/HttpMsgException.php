<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * 代码中用 throw new HttpMsgException('lang.fail'); 抛异常，不建议用throw new \ErrorException(__('lang.fail'), 400);
 */
class HttpMsgException extends Exception {

    public $data = [];

    /**
     * @param string $msg 异常提示信息
     * @param int $code 状态码
     * @param $data 返回数据
     * @param Throwable|null $previous
     */
    public function __construct(string $msg = 'lang.fail', int $code = 400, $data = [], Throwable $previous = null) {
        $msg = getTranslateMsg($msg);

        parent::__construct($msg, $code, $previous);

        !empty($data) && $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

}
