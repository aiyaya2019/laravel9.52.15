<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * 代码中用 throw new HttpMsgException('lang.fail'); 抛异常，不建议用throw new \ErrorException(__('lang.fail'), 400);
 */
class HttpMsgException extends Exception {

    public $data = [];

    public function __construct($msg = 'lang.fail', $code = 400, $data = [], Throwable $previous = null) {
        $msg = $this->getTranslateMsg((string)$msg);

        parent::__construct($msg, $code, $previous);

        !empty($data) && $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    /**
     * @Desc:翻译提示信息
     * @param string $msg 提示信息
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|mixed|string|null
     * @author: wanf
     * @Time: 2023/12/15 15:05
     */
    protected function getTranslateMsg(string $msg) {
        str_starts_with($msg, 'lang.') && $msg = __($msg);

        return $msg;
    }

}
