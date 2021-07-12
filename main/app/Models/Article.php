<?php
namespace App\Models;

use App\Models\ModelTrait\ModelTrait;
use Watson\Rememberable\Rememberable;
use App\Models\ModelTrait\StorageTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Article extends Model implements Auditable
{
    use AuditableTrait, SoftDeletes, StorageTrait, ModelTrait, Rememberable;

    protected $appends = ['type_name'];

    protected $casts = ['status' => 'bool'];

    protected $connection = 'main_sql';

    protected $fillable = ['title', 'thumb', 'thumb_wap', 'content', 'author_id', 'excerpt', 'status', 'cat_id', 'type'];

    protected $hidden = ['deleted_at'];

    protected $rememberCacheTag = 'article';

    public function author()
    {
        return $this->hasOne(Admin::class, 'id', 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'cat_id', 'id');
    }

    public function getThumbAttribute($value)
    {
        $style = request()->get('oss-image-style') ?: 'max';
        return $this->getOssImageStyle($value, $style);
    }

    public function getThumbWapAttribute($value)
    {
        $style = request()->get('oss-image-style') ?: 'max';
        return $this->getOssImageStyle($value, $style);
    }

    public function getTypeNameAttribute()
    {
        $name = isset(config('app.article')[$this->type]) ? config('app.article')[$this->type] : '未知类型';
        return $name;
    }

    public function setThumbAttribute($value)
    {
        $this->attributes['thumb'] = $this->getImagePath($value);
    }

    public function setThumbWapAttribute($value)
    {
        $this->attributes['thumb_wap'] = $this->getImagePath($value);
    }
}
