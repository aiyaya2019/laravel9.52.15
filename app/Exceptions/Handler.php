<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Exception;

class Handler extends ExceptionHandler {
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() {
        $this->reportable(function (Throwable $e) {
            // 不生效
            if ($e instanceof \Illuminate\Database\QueryException) {
                // 捕获数据库查询异常并记录日志
                Log::channel('sqllog')->info($e->getMessage());
            }
        });
    }

    public function render($request, Throwable $e) {
        if ($e instanceof NotFoundHttpException || $e instanceof MethodNotAllowedHttpException) {
            return returnData(404, $e->getMessage(), [], handleErrorData($e), $e);

        } elseif ($e instanceof ValidatorException) {
            // 验证器异常
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);

        } elseif ($e instanceof HttpMsgException) {
            // 逻辑代码抛异常
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);

        } elseif ($e instanceof Exception) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
            // return returnData(400, '代码错误，或检查代码中是否有接收异常', [], handleErrorData($e), $e);
        }

        return parent::render($request, $e);
    }

}
