<?php

namespace App\Http\Common;

/**
 * 全局常量类
 */
class Constant {
    const HOME_BANNER = 1;
    const LIST_BANNER = 2;

    const SM2_PUBLIC_KEY = '043d9d4cc71a285af936b36880fd4d6155c22957cd2c84ea313469065207fb951b9ef1db79d69af8886e91e833da1ebc6bfdde86e70f52923d6e042eaa147624c7';//sm2公钥
    const SM2_PRIVATE_KEY= 'a7763cd4fe7db2a2146fc09bf2d5e5a30e10c51b7e4bed00b3a26ec79ba78ff3';//sm2私钥

    const SM4_KEY = 'abcdef1699511091';//sm_key，sm4文件加解密用
    const SM4_IV = '1234561699511091';//sm_iv，sm4文件加解密用
}
