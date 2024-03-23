<?php

namespace App\Http\Logic;

class BaseLogic {

    public $user;

    public function __construct() {
        $user = auth('api')->user();
        $this->user = !empty($user) ? objectToArray($user) : [];
    }



}
