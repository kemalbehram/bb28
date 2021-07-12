<?php
namespace App\Models\ModelTrait;

use function App\Models\option;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
trait UserExtendTrait
{
    use StorageTrait;

    public function getAvatarAttribute()
    {
        if ($this->wechat) {
            return $this->attributes['avatar'];
        }
        if ($this->id == 100000) {
            $avatar = option('master_service_avatar');
            if ($avatar) {
                return $avatar;
            }
        }
        $name = substr($this->id, -1);
        $path = '/avatar-user/avatar-0' . $name . '.png';
        return $this->getOssImageStyle($path, 'avatar');
    }

    public function getIdHashAttribute()
    {
        return md5('id-hash:' . $this->id);
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setSafeWordAttribute($value)
    {
        $this->attributes['safe_word'] = Hash::make($value);
    }

    public function updateRequestedAt()
    {
        $ip         = request()->ip();
        $cache_name = __CLASS__ . '\updateRequestedAt' . ':' . $this->id . '==IP:' . $ip;
        if (Cache::has($cache_name)) {
            return $this;
        }
        Cache::put($cache_name, time(), 300);
        $this->requested_at = date('Y-m-d H:i:s');
        $this->requested_ip = $ip;
        $this->save();

        try {
            $server = request()->server();
            $data   = [
                'user_id'         => $this->id,
                'http_user_ip'    => $ip,
                'http_host'       => $server['HTTP_HOST'],
                'http_referer'    => $server['HTTP_REFERER'],
                'http_user_agent' => $server['HTTP_USER_AGENT'],
            ];
            DB::table('user_request')->insert($data);
        } catch (\Throwable $th) {
            return $this;
        }
        return $this;
    }
}
