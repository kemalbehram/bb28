<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    protected $casts = ['ref_str' => 'array', 'ref_id' => 'int'];

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'ref_id', 'ref_str', 'level', 'has_rebate'];

    public function childUser()
    {
        return $this->hasMany($this, 'ref_id', 'user_id');
    }

    public function children()
    {
        return $this->childUser()->with('children');
    }

    public static function createReference($user_id, $ref_id)
    {
        $ref_id = intval($ref_id);
        $data   = [
            'user_id' => $user_id,
            'ref_id'  => $ref_id,
        ];

        $ref_str = [$ref_id];

        $i = 0;
        for ($i = 0; $i < 4; $i++) {
            if (isset($ref_str[$i])) {
                $temp_code = $ref_str[$i];
                $temp      = UserReference::where('user_id', $temp_code)->first();
                if (!$temp) {continue;}
                $ref_str[] = $temp->ref_id;
            }
        }

        $data['ref_str'] = $ref_str;
        $data['level']   = count($ref_str);

        return UserReference::create($data);
    }

    public static function getReference($user_id)
    {
        $mine = self::where('user_id', $user_id)->first();
        if ($mine === null) {return null;}
        $ref = User::find($mine->ref_id);

        $result = [
            'nickname' => $ref->nickname,
            'avatar'   => $ref->avatar,
            'ref_code' => $ref->ref_code,
            'ref_id'   => $mine->ref_id,
        ];

        return $result;
    }

    public function superiors()
    {
        $items = User::whereIn('id', $this->ref_str)->get(['id', 'nickname']);
        return $items->toArray();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function parent()
    {
        return $this->hasOne(User::class, 'id', 'ref_id');
    }
}
