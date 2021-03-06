<?php
namespace App\Models;

use App\Models\ModelTrait\ModelTrait;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

function option($key, $format = 'string', $default = null)
{
    $option = Option::remember(3600)->find($key);

    if ($option === null) {
        return $default;
    }

    if ($format === 'string') {
        return $option->value;
    }

    if ($format === 'bool') {
        return $option->value == 1 ? true : false;
    }

    if ($format === 'int') {
        return intval($option->value);
    }

    return $option->value;
}

class Option extends Model implements Auditable
{
    use ModelTrait, Rememberable, AuditableTrait;

    public $incrementing = false;

    protected $connection = 'main_sql';

    protected $fillable = ['name', 'value'];

    protected $hidden = ['deleted_at'];

    protected $primaryKey = 'name';

    protected $rememberCacheTag = 'option';

    public function getValueAttribute($value)
    {
        $json = json_decode($value);
        return $json ?: $value;
    }

    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        $this->attributes['value'] = $value;
    }
}
