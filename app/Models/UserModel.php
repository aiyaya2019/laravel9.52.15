<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class UserModel extends BaseModel {

    protected $table = 'user';

    protected  $hidden = ['password'];

    /**
     * @Desc:数据表存的是正常的日期格式 2023-11-03 17:38:16，但查出来的是2023-11-03T17:38:16.000000Z。所以日期需格式化一下
     * @param DateTimeInterface $date
     * @return string
     * @author: wanf
     * @Time: 2023/11/17 17:55
     */
    protected function serializeDate(DateTimeInterface $date) {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

}
