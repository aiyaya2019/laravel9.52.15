<?php

namespace App\Http\BusinessLogic\Banner;

use App\Http\BusinessLogic\BaseLogic;
use App\Models\BannerModel;

class BannerLogic extends BaseLogic {

    protected $model;

    public function __construct() {
        $this->model = new BannerModel();
    }

    public function list() {
        $data = $this->model->getAll();
        return $data;
    }



}
