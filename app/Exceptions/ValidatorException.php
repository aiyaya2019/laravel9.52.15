<?php

namespace App\Exceptions;

use Exception;

/**
 * 验证器异常处理类，其他地方不用这个抛异常
 */
class ValidatorException extends Exception {

    public $data = [];

    /**
     * @param string $msg 异常提示信息
     * @param int $code 状态码
     * @param $data 返回数据
     * @param Throwable|null $previous
     */
    public function __construct(string $msg, int $code = 400, $data = [], Throwable $previous = null) {
        $msg = getTranslateMsg($msg);

        parent::__construct($msg, $code, $previous);

        if (!empty($data)) {
            $this->data = $data;
        }
    }

    public function getData() {
        return $this->data;
    }
}
