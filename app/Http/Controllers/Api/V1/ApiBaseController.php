<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ApiBaseController extends BaseController {

    public $user;

    public function __construct() {
        $user = auth('api')->user();
        $this->user = !empty($user) ? objectToArray($user) : [];
    }



}
