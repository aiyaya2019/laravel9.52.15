<?php

use Illuminate\Support\Facades\Log;
use Rtgm\sm\RtSm2;
use App\Http\Common\Constant;

/**
 * 对象转数组
 * @param object $object
 */
function objectToArray($object) {
    //先编码成json字符串，再解码成数组
    return !empty($object) ? json_decode(json_encode($object), true) : [];
}

/**
 * @Desc:返回数据给前端
 * @param $code code 状态码：200成功，201成功弹出确认窗口，300去登录，400失败
 * @param $msg 提示信息
 * @param $data 数据
 * @param $errDetails 错误详情
 * @return \Illuminate\Http\JsonResponse
 * @author: wanf
 * @Time: 2023/11/18 9:01
 */
function returnData($code = 200, $msg = '操作成功', $data = [], $errDetails = '', $exception = null) {
    if ($exception instanceof \Illuminate\Database\QueryException) {
        // sql异常，不返回具体异常信息给前端，可到日志中查看具体异常信息 err_details
        $msg = __('lang.error');
    }

    return response()->json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
        'err_details' => $errDetails,
    ]);
}

/**
 * @Desc:拼接详细报错信息
 * @param $exception
 * @return string
 * @author: wanf
 * @Time: 2023/11/24 14:46
 */
function handleErrorData($exception) {
    return sprintf('%s(%s)：%s', $exception->getFile(), $exception->getLine(), $exception->getMessage());
}

/**
 * @Desc:取消响应中的错误详情信息，中间件中记录详细日志需用到err_details字段，可设置不返回给前端
 * @param $response
 * @author: wanf
 * @Time: 2023/11/23 11:03
 */
function cancelResponseDetails($response) {
    $responseData = $response->getData();
    unset($responseData->err_details);
    $response->setData($responseData);

    return $response;
}

/**
 * @Desc:记录请求日志
 * @param int $reqStatus 接口请求状态
 * @param string $useTime 接口用时
 * @param $returnData 返回数据
 * @param string $model 日志通道
 * @return bool
 * @author: wanf
 * @Time: 2023/11/1 21:41
 */
function requestLog(int $reqStatus, string $useTime = '', $returnData = null, string $model = 'adminlog') {
    $params = request()->all();
    if (isset($params['s'])) {
        unset($params['s']);
    }

    $data = [
        'return_code' => $returnData['code'] ?? '',//返回状态码：200成功，201成功弹出确认窗口，300去登录，400失败
        'return_msg' => $returnData['msg'] ?? '',//返回信息提示
        'method' => request()->method(),//请求方式
        'port' => request()->getPort(),//端口
        'ip' => request()->ip(),//ip
        'url' => request()->url(),//url
        'return_data' => env('LOG_RECORD_RETURN_DATA') == 1 ? $returnData : '不记录返回数据',//返回数据
        'req_params' => $params,//请求参数
        'header' => env('LOG_HEADER_FORMAT') == 1 ? request()->header() : json_encode(request()->header(), JSON_UNESCAPED_UNICODE),
    ];

    if (env('LOG_FORMAT') == 1) {
        // 数组形式
        Log::channel($model)->info('reqStatus:'. $reqStatus .' useTime:' . $useTime .'ms  ' .print_r($data, true));

    } elseif (env('LOG_FORMAT') == 2) {
        // json形式
        Log::channel($model)->info('reqStatus:'. $reqStatus .' useTime:' . $useTime .'ms  ' .json_encode($data), JSON_UNESCAPED_UNICODE);

    } else {
        // 其他
        is_array($data['return_data']) && $data['return_data'] = json_encode($data['return_data'], JSON_UNESCAPED_UNICODE);
        is_array($data['req_params']) && $data['req_params'] = json_encode($data['req_params'], JSON_UNESCAPED_UNICODE);
        is_array($data['header']) && $data['header'] = json_encode($data['header'], JSON_UNESCAPED_UNICODE);

        $log = sprintf('return_code:%s, return_msg:%s, method:%s, port:%s, ip:%s, url:%s, return_data: %s, req_params:%s, header:%s', $data['return_code'], $data['return_msg'], $data['method'], $data['port'], $data['ip'], $data['url'], $data['return_data'], $data['req_params'], $data['header']);
        Log::channel($model)->info($log);
    }

    return true;
}

/**
 * @Desc:admin、api普通日志记录，排查bug用
 * @param int $code 状态码：200成功，201成功弹出确认窗口，300去登录，400失败
 * @param $data 需要记录的数据或信息
 * @return bool
 * @author: wanf
 * @Time: 2023/11/24 14:44
 */
function recordLog(int $code, $data = null) {

    $url = request()->url();

    $model = 'adminrecordlog';

    str_contains($url, '/api/') && $model = 'apirecordlog';

    $logData = [
        'code' => $code,
        'url' => $url,//url
        'return_data' => $data,
    ];

    if (env('RECORD_LOG_FORMAT') == 1) {
        // 数组形式
        Log::channel($model)->info(print_r($logData, true));

    } elseif (env('RECORD_LOG_FORMAT') == 2) {
        // json形式
        Log::channel($model)->info(json_encode($logData, JSON_UNESCAPED_UNICODE));

    } else {
        // 其他
        is_array($logData['return_data']) && $logData['return_data'] = json_encode($logData['return_data'], JSON_UNESCAPED_UNICODE);
        $log = sprintf('code:%s, url:%s, return_data:%s', $logData['code'], $logData['url'], $logData['return_data']);
        Log::channel($model)->info($log);
    }

    return true;
}

/**
 * @Desc:curl请求
 * @param string $url 请求url
 * @param string $method 请求方式
 * @param $params 请求参数
 * @param $isJson 传参形式：true表示以json方式传递
 * @param $headers 请求头
 * @param $cookie cookie
 * @param $returnCookie
 * @return array|bool|string
 * @author: wanf
 * @Time: 2023/11/24 14:49
 */
function curlRequest(string $url, string $method, $params = null, $isJson = false, $headers = [], $cookie = '', $returnCookie = 0){

    $method = strtoupper($method);

    // 参数以json格式发送
    if ($isJson) {
        is_array($params) && $params = json_encode($params);
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($params),
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ];
    }

    $curl = curl_init();// 初始化 cURL

    curl_setopt($curl, CURLOPT_URL, $url);//设置请求url
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);//设置是否自动跟随重定向。如果启用了这个选项，cURL 将自动遵循服务器返回的任何重定向
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);//设置是否在重定向时自动设置 Referer 头。如果启用了这个选项，cURL 将在发生重定向时，自动将上一个请求的 URL 作为 Referer 头部添加到下一个请求。默认情况下，CURLOPT_AUTOREFERER 是启用的，这意味着 cURL 在进行重定向时会自动设置 Referer 头。这对于模拟浏览器行为时很有用，因为浏览器通常在发起请求时会自动设置 Referer 头
    curl_setopt($curl, CURLOPT_REFERER, "http://XXX");//设置请求的 Referer（引用页）头。Referer 头部表示请求的来源页面，即请求是从哪个页面发起的。
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);//设置与服务器建立连接的超时时间。该选项定义了 cURL 尝试连接到服务器的最长时间，超过这个时间则认为连接失败。
    curl_setopt($curl, CURLOPT_TIMEOUT, 7);//设置整个 cURL 操作的最长时间。它包括连接和传输的所有时间，如果在设置的时间内未完成整个操作，cURL 将被终止。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//设置是否返回响应内容

    switch ($method) {
        case 'POST':
            curl_setopt($curl, CURLOPT_POST, true);//设置 cURL 请求的 HTTP 请求方法。1 表示启用 POST 请求方法。如果设置为 0，则表示禁用 POST 方法，采用其他默认的请求方法（如 GET）。当 CURLOPT_POST 被设置为 1 时，通常需要使用 CURLOPT_POSTFIELDS 设置 POST 请求的数据
            if (!empty($params)) {
                // http_build_query()构建 URL 查询字符串的函数。它将一个关联数组转换为 URL 查询字符串的形式，以便在 URL 中传递参数。这个函数非常方便，特别是在构建 HTTP GET 请求时。输出例如name=John+Doe&age=30&city=New+York
                $dataStr = is_array($params) ? http_build_query($params) : $params;
                curl_setopt($curl, CURLOPT_POSTFIELDS, $dataStr);//设置请求参数
            }
            break;
        default:
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);//设置请求方法
            break;
    }

    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);//设置 HTTP 请求的 Cookie。通过设置这个选项，你可以在 cURL 请求中包含特定的 Cookie。
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);//用于控制是否将 HTTP 响应头一同输出。如果启用这个选项，cURL 将把 HTTP 响应头和响应体一同返回，你可以通过 curl_exec() 获取完整的响应，包括头部和内容。
    }

    curl_setopt($curl, CURLOPT_MAXREDIRS, 2);// 设置最大重定向次数为2
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);//设置请求头
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);// 启用输出 HTTP 头部。可在 curl_getinfo() 函数中获取请求的头部信息。

    $response = curl_exec($curl);//执行 cURL 请求

    // curl_getinfo() 函数用于获取 cURL 句柄的信息。你可以使用这个函数来获取有关最近一次 cURL 请求的详细信息，包括请求头、响应头、响应码、总时间等。
    curl_getinfo($curl);
    curl_getinfo($curl, CURLINFO_HTTP_CODE);//获取响应的 HTTP 状态码

    curl_close($curl);//关闭 cURL 资源

    if ($returnCookie) {
        list($header, $body) = explode("\r\n\r\n", $response, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $result['cookie']  = substr($matches[1][0], 1);
        $result['content'] = $body;
        return $result;

    } else {
        return $response;
    }
}

/**
 * curl请求。发送Json对象数据。和curlRequest($url, 'post', $params, true)用法 作用一样
 * @param $url 请求url
 * @param $data 发送的json字符串/数组
 * @return array
 */
function jsonPost($url, $data = NULL) {

    // 初始化 cURL
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);//设置请求url
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//控制是否验证对等证书。当启用时，cURL 将验证远程服务器的 SSL 证书。
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);//控制是否验证主机名。当启用时，cURL 将验证远程服务器的 SSL 证书中的主机名是否与请求的主机名匹配。
    if(!$data){
        return 'data is null';
    }

    is_array($data) && $data = json_encode($data);

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//设置请求参数
    curl_setopt($curl, CURLOPT_HEADER, 0);//用于控制是否将 HTTP 响应头一同输出。如果启用这个选项，cURL 将把 HTTP 响应头和响应体一同返回，你可以通过 curl_exec() 获取完整的响应，包括头部和内容。
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json; charset=utf-8',
        'Content-Length:' . strlen($data),
        'Cache-Control: no-cache',
        'Pragma: no-cache'
    ]);//设置请求头
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//设置是否返回响应内容

    $response = curl_exec($curl);//执行 cURL 请求

    $errorno = curl_errno($curl);
    if ($errorno) {
        return $errorno;
    }
    curl_close($curl);//关闭 cURL 资源

    return $response;
}

/**
 * @Desc:sm2加密
 * @param string $string 待加密明文
 * @return mixed
 * @author: wanf
 * @Time: 2023/11/9 13:56
 */
function sm2Encrypt(string $string) {
    $sm2 = new RtSm2('base64', false);

    return $sm2->doEncrypt($string, Constant::SM2_PUBLIC_KEY);
}

/**
 * @Desc:sm2解密
 * @param string $string 待解密密文
 * @return mixed
 * @author: wanf
 * @Time: 2023/11/9 13:56
 */
function sm2Decrypt(string $string) {
    $sm2 = new RtSm2('base64', false);

    return $sm2->doDecrypt($string, Constant::SM2_PRIVATE_KEY);
}




