<?php

namespace App\Http\Logic;

use App\Models\BannerModel;

class BannerLogic extends BaseLogic {

    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = new BannerModel();
    }

    public function list() {
        $data = $this->model->getAll();
        return $data;
    }



}
