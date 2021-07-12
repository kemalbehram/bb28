<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Watson\Rememberable\Rememberable;
use App\Models\UserWallet\WalletTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\LottoModule\UserBetTrait;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\ModelTrait\UserExtendTrait;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, Auditable
{
    use UserExtendTrait, AuditableTrait, WalletTrait, UserBetTrait, Rememberable;

    public $incrementing = false;

    protected $appends = ['avatar'];

    protected $casts = ['disable' => 'bool', 'transfer' => 'bool', 'withdraw' => 'bool', 'bet' => 'bool', 'robot' => 'bool', 'trial' => 'bool', 'wechat' => 'bool', 'bind' => 'bool'];

    protected $connection = 'main_sql';

    protected $fillable = ['id', 'nickname', 'real_name', 'password', 'safe_word', 'mobile', 'contact_qq', 'status', 'robot', 'trial', 'wechat', 'openid', 'avatar', 'bind', 'requested_at', 'requested_ip', 'created_ip'];

    protected $hidden = ['password', 'safe_word'];

    public function Chat()
    {
        return $this->hasMany(Chat::class);
    }

    public function CheckIsAgent()
    {
        if ($this->agent->status === true) {
            return true;
        }
        $this->trial === true && real()->exception('该功能仅对代理开放 您无权操作');
    }

    public function CheckIsTrial()
    {
        if ($this->trial === null) {
            return null;
        }
        $this->trial === true && real()->exception('您为试玩账号 请注册后再操作');
    }

    public function CheckIsBlock()
    {
        if ($this->block_time === null) {
            return null;
        }

        $this->block_time == 1  && real()->exception('您的账号已被禁用');
    }

    public function agent()
    {
        return $this->hasOne(UserAgent::class, 'user_id', 'id')->withDefault([
            'status'         => false,
            'transfer_rate'  => env('AGENT_TRANSFER_RATE', '0.020'),
            'transfer_refer' => env('AGENT_TRANSFER_REFER', '0.005'),
            'visible_admin'  => false,
            'visible_agent'  => false,
            'advance'        => '0.000',
            'contact_qq'     => null,
            'intro'          => null,
        ]);
    }

    public function bankCard()
    {
        return $this->hasOne(BankCard::class, 'user_id', 'id')->orderBy('id', 'desc');
    }

    public function checkIP()
    {
        $result               = [];
        $temp                 = explode('.', $this->requested_ip);
        $requested_ip         = $temp[0] . '.' . $temp[1] . '.';
        $result['req_regexp'] = $this->where('requested_ip', 'like', $requested_ip . '%')->count();
        $result['req_100']    = $this->where('requested_ip', $this->requested_ip)->count();

        $temp                 = explode('.', $this->created_ip);
        $created_ip           = $temp[0] . '.' . $temp[1] . '.';
        $result['reg_regexp'] = $this->where('created_ip', 'like', $created_ip . '%')->count();
        $result['reg_100']    = $this->where('created_ip', $this->created_ip)->count();
        return $result;
    }

    public function createOption()
    {
        $data = [
            'user_id'  => $this->id,
            'bet_chat' => true,
        ];
        return UserOption::create($data);
    }

    public function option()
    {
        return $this->hasOne(UserOption::class, 'user_id', 'id');
    }

    public function safeWordCheck($word)
    {
        return Hash::check($word, $this->safe_word);
    }

    public function setIdAttribute($value)
    {
        if ($value === 'trial') {
            $id                     = '10' . mt_rand(100000, 999999);
            $this->attributes['id'] = $id;
        } elseif ($value === 'robot') {
            $id                     = '120' . mt_rand(10000, 99999);
            $this->attributes['id'] = $id;
        } else {
            $this->attributes['id'] = mt_rand(100000, 999999);
        }
    }

    public function setMobileAttribute($value)
    {
        if ($value === 'trial') {
            $id                         = $this->id;
            $this->attributes['mobile'] = '100' . $id;
        } elseif ($value === 'robot') {
            $id                         = $this->id;
            $this->attributes['mobile'] = '120' . $id;
        } else {
            $this->attributes['mobile'] = $value;
        }
    }

    public function userLevel()
    {
        $level_exp = $this->option->level_exp;
        $config    = config('app.user_level.rule_table');
        $result    = [];
        foreach ($config as $level) {
            if ($level_exp < $level['exp_target']) {
                continue;
            }
            $result = $level;
        }
        $result['level_exp'] = $level_exp;
        $result['exp_diff']  = 0;

        if ($result['level_index'] < 30) {
            $next_level         = $config[$result['level_index'] + 1];
            $result['exp_diff'] = $next_level['exp_target']; //intval( - $level_exp);
        }

        return $result;
    }
}
