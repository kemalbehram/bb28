<?php
namespace App\Models;

use App\Models\ModelTrait\ModelTrait;
use Watson\Rememberable\Rememberable;
use App\Models\ModelTrait\StorageTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

class OptionAward extends Model implements Auditable
{
    use SoftDeletes, ModelTrait, StorageTrait, Rememberable, AuditableTrait, SortableTrait;

    public $incrementing = false;

    protected $casts = ['params' => 'array', 'status' => 'bool'];

    protected $connection = 'main_sql';

    protected $fillable = ['title', 'status', 'sort', 'type', 'amount', 'target', 'unit', 'color', 'intro', 'params', 'model'];

    protected $hidden = ['deleted_at'];

    protected $primaryKey = 'type';

    protected $rememberCacheTag = 'option_award';

    protected $table = 'option_awards';
}
