<?php

namespace App\Http\Controllers\Api\V1;


use App\Models\User;
use App\Http\Logic\UserLogic;
use App\Http\Controllers\BaseController;
use Exception;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MiniApp\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeChatController extends BaseController {

    public function guard() {
        return Auth::guard('api');
    }

    private $config;

    public function __construct() {
        $this->setConfig();
    }

    public function setConfig() {
        // wxc39e9f84b3fb020e
        $this->config = [
            'app_id' => 'wxb3606bf74883b55f',
            'secret' => 'b1b93a1668a70e1fd6074d9849723b72',
            'js_code' => '',
            'grant_type' => 'authorization_code',
        ];
    }

    /**
     * @Desc:小程序授权登录，获取openid、session_key
     * @return \Illuminate\Http\JsonResponse * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author: wanf
     * @Time: 2023/12/11 19:25
     */
    public function login(Request $request) {
        $code = $request->input('code', '');

        if (empty($code)) {
            return returnData(400, 'code参数错误');
        }

        $this->config['js_code'] = $code;

        try {
            $app = new Application($this->config);
            $utils = $app->getUtils();

            $response = $utils->codeToSession($code);

            (new UserLogic())->saveUserInfo(['openid' => $response['openid']]);

            $user = User::where('openid', $response['openid'])->first();

            $token = auth('api')->login($user);//$user需要是first()查出来的对象类型

            $response['token'] = 'Bearer  ' .$token;

            return returnData(200, __('lang.login_success'), $response);

        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }
    }

    /**
     * @Desc:获取手机号
     * @return \Illuminate\Http\JsonResponse * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author: wanf
     * @Time: 2023/12/11 19:26
     */
    public function getPhone() {
        $code = request('code');
        if (empty($code)) {
            return returnData(400, 'code参数错误');
        }

        $this->config['js_code'] = $code;

        try {
            $app = new Application($this->config);
            $response = $app->getClient()->postJson('wxa/business/getuserphonenumber', ['code' => (string) $code]);

            (new UserLogic())->saveUserInfo(['openid' => $response['openid'], 'phone' => $response['phone_info']['phoneNumber']]);

            return returnData(200, __('lang.success'), $response['phone_info']['phoneNumber']);

        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }
    }

    /**
     * @Desc:生成小程序码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author: wanf
     * @Time: 2023/12/16 11:01
     */
    public function getMiniCode(Request $request) {
        $scene = (string)$request->input('scene', '');

        try {
            $app = new Application($this->config);
            $response = $app->getClient()->postJson('/wxa/getwxacodeunlimit', [
                'scene' => $scene,
                'page' => 'pages/index/index',
                'width' => 430,
                'check_path' => false,
            ]);

            $path = $response->saveAs('/tmp/wxacode-123.png');

        } catch (Exception $e) {
            return returnData($e->getCode(), $e->getMessage(), [], handleErrorData($e), $e);
        }

    }

}
