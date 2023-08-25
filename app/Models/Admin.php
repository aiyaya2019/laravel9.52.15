<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {
    protected $table = 'admin';

    protected $fillable = [
        'name', 'password',
    ];

    /**
     * Auth::guard('admin')->user();获取的用户信息，隐藏一下字段
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at',
    ];
}
