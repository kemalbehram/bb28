<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminOpt extends Model
{
    public $mapping = [
        'mobileUpdate' => '更新手机号',
        'password'     => '找回密码',
        'register'     => '账号注册',
        'safeWord'     => '安全码设置',

    ];

    protected $connection = 'main_sql';

    protected $fillable = ['admin_id', 'user_id', 'mobile', 'type', 'opt_value'];

    public function admin()
    {
        return $this->hasOne(admin::class, 'id', 'admin_id');
    }
}
