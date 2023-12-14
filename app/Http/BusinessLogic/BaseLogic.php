<?php

namespace App\Http\BusinessLogic;

class BaseLogic {

    public $user;

    public function __construct() {
        $user = auth('api')->user();
        $this->user = !empty($user) ? objectToArray($user) : [];
    }



}
