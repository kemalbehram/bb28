<?php
namespace App\Models\LottoModule\Models;

use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LottoModule\Models\LottoPlayType;

class LottoUserFavorite extends Model
{
    use Rememberable;

    public $rememberCacheTag = 'LottoUserFavorite';

    protected $appends = ['lotto_title', 'play_title'];

    protected $casts = ['status' => 'bool'];

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'lotto_name', 'play_type', 'status'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $table = 'lotto_user_favorites';

    public static function getItemsForApp($user_id)
    {
        $items  = self::where('user_id', $user_id)->where('status', 1)->orderBy('updated_at', 'desc')->get();
        $result = [];

        if ($items === null) {
            return $result;
        }

        foreach ($items as $item) {
            $config = LottoConfig::remember(600)->find($item->lotto_name);
            if ($config === null || $config->visible === false) {
                continue;
            }
            $result[] = $item;
        }

        return array_values($result);
    }

    public function getLottoTitleAttribute()
    {
        if (!$this->lotto_name) {
            return null;
        }

        $item = LottoConfig::remember(600)->find($this->lotto_name);
        if ($item === null) {
            return null;
        }
        return $item->subtitle;
    }

    public function getPlayTitleAttribute()
    {
        $config = LottoPlayType::getFormatResult();

        return $config[$this->play_type]['title'];
    }

    public static function getStatusForApp($user_id)
    {
        $items  = self::where('user_id', $user_id)->get();
        $result = [];

        if ($items === null) {
            return $result;
        }

        foreach ($items as $item) {
            $index          = $item->lotto_name . '_' . $item->play_type;
            $result[$index] = $item->status;
        }

        return $result;
    }
}
