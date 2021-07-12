<?php
namespace App\Models\LottoModule\Models;

use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;

class LottoUserOdds extends Model
{
    use Rememberable;

    public $rememberCacheTag = 'LottoUserOdds';

    protected $connection = 'main_sql';

    protected $fillable = ['user_id', 'place', 'odds'];

    protected $table = 'lotto_user_odds';

    public static function userOdds($user_id, $lotto_name = 'all')
    {
        $result = [];
        $items  = self::remember(60)->where('user_id', 100000);
        $items  = $items->get();
        if ($items !== null) {
            foreach ($items as $item) {
                if ($item->lotto_name !== 'all' && $item->lotto_name !== $lotto_name) {
                    continue;
                }
                $result[$item->place] = $item->odds;
            }
        }

        $items = self::remember(60)->where('user_id', $user_id);
        $items = $items->get();
        if ($items !== null) {
            foreach ($items as $item) {
                if ($item->lotto_name !== 'all' && $item->lotto_name !== $lotto_name) {
                    continue;
                }
                $result[$item->place] = $item->odds;
            }
        }
        return $result;
    }
}
