<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Models\Option;
use App\Models\DataStats;
use App\Packages\Utils\SMS;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserReference;
use function App\Models\option;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\ModelTrait\ThisUserTrait;
use App\Models\LottoModule\Models\LottoConfig;
use App\Models\LottoModule\Models\LottoPlayType;

class UserAuthController extends Controller
{
    use ThisUserTrait;

    public function bindAccount(Request $request)
    {
        $rule = [
            'mobile'   => 'required|mobile|unique:users',
            'password' => 'required|min:6|max:18',
            'ver_code' => 'required|int',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        if ($request->ver_code != option('sms_un_check_code')) {
            $key  = 'bind:' . $request->mobile;
            $temp = cache()->get($key);
            if (!$temp || $request->ver_code != $temp['ver_code']) {
                return real()->error('ver_code.check.invalid');
            }
            cache()->forget($key);
        }
        $this->user->mobile   = $request->mobile;
        $this->user->password = $request->password;
        $this->user->bind     = 1;
        $this->user->save() || real()->exception('绑定账号失败');
        return real()->success('绑定成功');
    }

    public function checkSafeBase()
    {
        $safe = config('wechat.allow_base');
        $path = url()->previous();
        !in_array($path, $safe) && real()->exception('request.fail');
        return true;
    }

    public function get()
    {
        $wallet = $this->user->wallet;
        $wallet || $this->user->createWallet();

        $option = $this->user->option;
        $option || $this->user->createOption();

        $result = [
            'id'         => $this->user->id,
            'id_hash'    => $this->user->id_hash,
            'nickname'   => $this->user->nickname,
            'real_name'  => $this->user->real_name,
            'avatar'     => $this->user->avatar,
            'mobile'     => $this->user->mobile,
            'contact_qq' => $this->user->contact_qq,
            'status'     => $this->user->status,
            'safe_word'  => $this->user->safe_word ? true : false,
            'wallet'     => $this->user->wallet,
            'option'     => $this->user->option->makeHidden(['fd_tax']),
            'trial'      => $this->user->trial,
            'bind_bank'  => $this->user->bankCard()->count() ? true : false,
            'is_agent'   => $this->user->agent->status,
            'agent'      => $this->user->agent,
            'wechat'     => $this->user->wechat,
            'bind'       => $this->user->bind,
            'ref_info'   => UserReference::getReference($this->user->id),
            'level'      => $this->user->userLevel(),
            'ws_host'    => env('WS_HOST') . ':' . env('WS_PORT'),
            //'ws_host' => "http://localhost:6001",
        ];

        return real($result)->success();
    }

    public function getToken(Request $request)
    {
        $rule = [
            'token' => 'required',
        ];
        $data = $request->only(array_keys($rule));
        real()->validator($data, $rule);
        $access_token = Cache::pull($request->token); //cache($request->token);//Cache::pull('key')
        $ttl          = auth('users')->factory()->getTTL() * 60;
        $result       = [
            'access_token' => $access_token,
            'token_type'   => 'bearer',
            'expires_in'   => $ttl,
        ];
        return real($result)->success();
    }

    public function login(Request $request)
    {
        $ip = $request->ip();
        $request->input('state') || $this->checkSafeBase();
        if ($request->is_wechat == true) {
            $user = User::where('openid', $request->openid)->first();
            if (empty($user)) {
                return real()->error('登录出错');
            }
            $token = auth('users')->tokenById($user->id);
            $key   = md5(md5($request->openid) . time());
            cache([$key => $token]);
        } else {
            $credentials = [
                'mobile'   => $request->mobile,
                'password' => $request->password,
            ];

            $token = auth('users')->attempt($credentials);
            $token || real()->exception('login.request.failed');
            $user = User::where('mobile', $request->mobile)->first();
        }
        $ttl = auth('users')->factory()->getTTL() * 60;

        //注册IP限制
        $ip_white = explode_break(option('ip_white'));
        $ip_limit = option('ip_limit');
        if (in_array($ip, $ip_white) === false && $user->trial === false && $user->agent->status !== true) {
            $count = User::where('requested_ip', $ip)->where('id', '!=', $user->id)->count();
            if ($ip_limit > 0 && $count >= $ip_limit) {
                return real()->error('您的登陆IP有过多用户登陆 请尝试更换电脑');
            }
        }

        $user->disable == 1 && real()->exception('您的账号被封禁 请联系QQ客服处理');

        $message = ['audio' => 'lotto_bet_open'];
        PushEvent::name('OtherLogin')->toUser($user->id)->data($message);

        $user->token        = $token;
        $user->requested_ip = $ip;
        $user->save();

        $this->user = $user;
        if ($request->is_wechat == true) {
            $redirect_login = config('wechat.redirect_login') . $key;
            header("Location:$redirect_login");
            dd($redirect_login);
        }
        $result = [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $ttl,
            'user_info'    => $this->get()['data'],
        ];

        sleep(1);
        return real($result)->success('login.request.success');
    }

    public function optionUpdate(Request $request)
    {
        $option = $this->user->option;

        isset($request->bet_chat) && $option->bet_chat     = $request->bet_chat;
        isset($request->bet_hot) && $option->bet_hot       = $request->bet_hot;
        isset($request->bet_miss) && $option->bet_miss     = $request->bet_miss;
        isset($request->bet_model) && $option->bet_model   = $request->bet_model;
        isset($request->user_check) && $option->user_check = $request->user_check;
        isset($request->block_time) && $option->block_time = $request->block_time;

        $save = $option->save();

        $save || real()->exception('option.update.failed');
        return real($request->toArray())->success('option.update.success');
    }

    public function passwordUpdate(Request $request)
    {
        $this->user && $this->user->CheckIsTrial();
        $rule = [
            'mobile'   => 'required',
            'password' => 'required|min:6|max:18',
            'ver_code' => 'required',
        ];

        $data = $request->all();
        real()->validator($data, $rule);

        $check = SMS::check('password', $request->mobile, $request->ver_code);
        $check || real()->exception('ver_code.invalid');

        $user = User::where('mobile', $request->mobile)->first();
        $user || real()->exception('this.user.notexist');

        $user->password = $request->password;
        $temp           = $user->save();
        $temp || real()->exception('password.reset.failed');

        return real()->success('password.reset.success');
    }

    public function randNickname()
    {
        $arr_1    = config('app.nickname.arr_1');
        $arr_2    = config('app.nickname.arr_2');
        $rand_1   = rand(0, 331);
        $rand_2   = rand(0, 325);
        $nickname = $arr_1[$rand_1] . $arr_2[$rand_2];
        $result   = ['nickname' => $nickname];

        return real($result)->success();
    }

    public function register(Request $request)
    {
        $this->checkSafeBase();
        $rule = [
            'mobile'   => 'required|unique:users',
            'password' => 'required|min:6|max:18',
            'nickname' => 'required|max:12',
            'ver_code' => 'required|int',
        ];

        $message = ['mobile.mobile' => '手机号不符合注册规则 请尝试更换'];

        $data = $request->all();
        real()->validator($data, $rule, $message);

        if ($request->ver_code != option('sms_un_check_code')) {
            $key  = 'register:' . $request->mobile;
            $temp = cache()->get($key);
            if (!$temp || $request->ver_code != $temp['ver_code']) {
                return real()->error('ver_code.check.invalid');
            }
            cache()->forget($key);
        }

        //注册IP限制
        $ip       = $request->ip();
        $ip_white = explode_break(option('ip_white'));
        $ip_limit = option('ip_limit');
        if (in_array($ip, $ip_white) === false && $ip_limit > 0) {
            $count = User::where('requested_ip', $ip)->where('trial', 0)->count();
            if ($ip_limit > 0 && $count >= $ip_limit) {
                real()->exception('该IP已注册多个账号 暂不支持注册更多账号');
            }
        }

        DB::beginTransaction();

        if ($request->ref_id) {
            $temp = User::find($request->ref_id);
            $temp || real()->exception('该推荐用户不存在 请重试');
            $temp->robot && real()->exception('该推荐码用户不能绑定下级');
            $temp->trial && real()->exception('该推荐码为试用账户 不能绑定下级');
        }

        $nickname = $request->nickname ?? 'user-' . Str::random(8);

        $data = [
            'id'         => 'user',
            'mobile'     => $request->mobile,
            'password'   => $request->password,
            // 'safe_word'  => $request->password,
            'nickname'   => $nickname,
            'status'     => 1,
            'wechat'     => 0,
            'bind'       => 1,
            'robot'      => false,
            'trial'      => false,
            'created_ip' => $ip,
        ];

        $user = User::create($data);
        $user || real()->exception('register.failed.retry');
        $user->createWallet();
        $user->createOption();

        if ($request->ref_id) {
            UserReference::createReference($user->id, $request->ref_id);
        }

        DB::commit();

        if ($request->method == 'login') {
            return $this->login($request);
        }

        return real()->success('register.request.success');
    }

    public function robotAdd(Request $request)
    {
        DB::beginTransaction();

        $nickname = config('app.robot.nickname');
        $rand     = mt_rand(0, count($nickname));
        $nickname = $nickname[$rand];
        $avatar   = 'https://ooo28-public.oss-accelerate.aliyuncs.com/yl28/common-avatar/avatar_' . mt_rand(0, 131) . '.jpg';
        $data     = [
            'id'         => 'robot',
            'mobile'     => 'robot',
            'password'   => 'robot112233',
            'nickname'   => $nickname,
            'status'     => 1,
            'wechat'     => mt_rand(0, 100) > 80 ? 0 : 1,
            'avatar'     => $avatar,
            'robot'      => true,
            'trial'      => false,
            'real_name'  => '机器人',
            'created_ip' => $request->ip(),
        ];

        $user = User::create($data);
        $user || real()->exception('register.failed.retry');
        $user->createWallet();
        $user->createOption();
        DB::commit();

        return real()->success('机器人创建成功');
    }

    public function safeWordCheck(Request $request)
    {
        $safe_word = $request->safe_word;
        $check     = Hash::check($safe_word, $this->user->safe_word);
        $check || real()->exception('safe_word.check.error');
        return real()->success('safe_word.check.success');
    }

    public function security(Request $request)
    {
        $this->user->CheckIsTrial();
        $rule = [
            'safe_word' => 'required',
            'ver_code'  => 'required',

        ];

        $data = $request->all();
        real()->validator($data, $rule);

        $check = Hash::check($data['safe_word'], $this->user->safe_word);
        $check || real()->exception('safe_word.check.error');
        $check = SMS::check('security', $this->user->mobile, $request->ver_code);
        $check || real()->exception('ver_code.invalid');

        $result['token']   = mt_rand(100000, 999999);
        $result['user_id'] = $this->user->id;

        return real($result)->success('security.check.success');
    }

    public function stats(Request $request)
    {
        $date_start = $request->date_start ?: date('Y-m-d');
        $date_end   = $request->date_end ?: date('Y-m-d');
        $date_end   = $date_end . ' 23:59:59';

        $stats = new DataStats($date_start, $date_end, $this->user->id);

        $bet     = $stats->bet();
        $red_bag = $stats->redBag();
        $result  = [
            'bet_profit'      => toDecimal($bet->bonus - $bet->total),
            'bet_total'       => toDecimal($bet->total),
            'bet_bonus'       => toDecimal($bet->bonus),
            'user_award'      => toDecimal($stats->userAward()->total),
            'wallet_recharge' => toDecimal($stats->walletRecharge()->total),
            'wallet_withdraw' => toDecimal($stats->walletWithdraw()->total),
            'transfer_in'     => toDecimal($stats->transferUser('payee')->total),
            'transfer_out'    => toDecimal($stats->transferUser('drawee')->total),
        ];

        return real($result)->success();
    }

    public function statsLotto(Request $request)
    {
        $lottos = LottoConfig::remember(600)->where('visible', '1')->orderBy('sort', 'asc')->get();
        $dates  = [];
        for ($i = 0; $i < 7; $i++) {
            $temp    = date('Y-m-d', strtotime('-' . $i . ' day'));
            $dates[] = $temp;
        }

        $data = [];

        $type_config = LottoPlayType::getFormatResult();
        foreach ($lottos as $lotto) {
            foreach ($lotto->types as $play_type) {
                $lotto_type        = $lotto->name . ':' . $play_type;
                $temp              = [];
                $data[$lotto_type] = [
                    'play_type'  => $play_type,
                    'lotto_name' => $lotto->name,
                    'title'      => $lotto->subtitle . $type_config[$play_type]['title'],
                    'total'      => [
                        'total'     => 0,
                        'bonus'     => 0,
                        'profit'    => 0,
                        'effective' => 0,
                    ],
                ];

                $user_id = $this->user->id;
                foreach ($dates as $date_index => $date) {
                    $date_start   = $date;
                    $date_end     = $date . ' 23:59:59';
                    $cache_name   = 'userStatsLottoABC:' . $user_id . $lotto_type . $date_start . $date_end;
                    $cache_second = ($date_start !== date('Y-m-d')) ? 86400 : 60;
                    $stats        = cache()->remember(
                        $cache_name,
                        $cache_second,
                        function () use ($date_start, $date_end, $user_id, $date_index, $lotto, $play_type) {
                            $stats = new DataStats($date_start, $date_end, $user_id);
                            return $stats->lottoStats($lotto->name, $play_type);
                        }
                    );

                    foreach ($stats as $key => $value) {
                        $data[$lotto_type]['total'][$key] += $value;
                    }

                    $stats->date = $date;

                    $date_key                     = 'stats_day_' . ($date_index + 1);
                    $data[$lotto_type][$date_key] = $stats;
                }
            }
        }
        // dd($data);

        $result = ['items' => array_values($data)];

        return real($result)->success();
    }

    //试玩账户
    public function trial(Request $request)
    {
        $this->checkSafeBase();
        DB::beginTransaction();

        // $nickname = $this->randNickname()['data']['nickname'];
        $data = [
            'id'         => 'trial',
            'mobile'     => 'trial',
            'password'   => 'aa112233',
            'nickname'   => '试玩账户',
            'status'     => 1,
            'robot'      => false,
            'trial'      => true,
            'real_name'  => '试玩账户',
            'created_ip' => $request->ip(),
        ];

        $user = User::create($data);
        $user || real()->exception('register.failed.retry');
        $user->createWallet();
        $user->createOption();

        DB::commit();

        $user->wallet->balance('user.trial.recharge')->plus(5000);

        $request->offsetSet('mobile', $user->mobile);
        $request->offsetSet('password', $data['password']);
        return $this->login($request);
    }

    public function update(Request $request)
    {
        $rule = ['method' => 'required'];
        $data = $request->all();
        real()->validator($data, $rule);
        $method = $request->method;
        return $this->$method($request);
    }

    public function wechatLogin(Request $request)
    {
        if ($request->input('code')) {
            return $this->getAccessToken($request->input('code'), $request->input('state'));
        } else {
            $request_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
            $ref         = empty($request->ref_id) ? 'default' : $request->ref_id;
            $query       = [
                'appid'         => config('wechat.appid'),
                'redirect_uri'  => 'http://' . config('wechat.base_url') . config('wechat.redirect_uri'),
                'response_type' => 'code',
                'scope'         => 'snsapi_userinfo',
                'state'         => $ref, //'123#wechat_redirect',
            ];
            $request_url .= http_build_query($query);
            header("Location:$request_url");
            dd($request_url);
        }
    }

    private function contactQqUpdate(Request $request)
    {
        $this->user->CheckIsTrial();
        DB::beginTransaction();
        $rule    = ['contact_qq' => 'required|min:5|max:14'];
        $data    = $request->all();
        $message = ['contact_qq.required' => '请输入您的联系QQ'];
        real()->validator($data, $rule, $message);
        $user = $this->user;
        $user->contact_qq && real()->exception('您已经登记过联系QQ 暂不支持修改');

        $user->contact_qq = $request->contact_qq;
        $save             = $user->save();

        $save || real()->exception('contact_qq.update.failed');

        DB::commit();

        $data['wallet'] = $user->wallet;
        return real($data)->success('contact_qq.update.success');
    }

    private function getAccessToken($code, $state)
    {
        $request_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $param       = [
            'appid'      => config('wechat.appid'),
            'secret'     => config('wechat.app_scrept'),
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
        $request_url .= http_build_query($param);
        $data         = json_decode(file_get_contents($request_url));
        $access_token = $data->access_token;
        $openid       = $data->openid;
        $user_url     = 'https://api.weixin.qq.com/sns/userinfo?';
        $query        = [
            'access_token' => $access_token,
            'openid'       => $openid,
            'lang'         => 'zh_CN',
        ];
        $user_url .= http_build_query($query);
        $user_info = json_decode(file_get_contents($user_url));
        $condition = [
            'openid' => $user_info->openid,
        ];

        $user = User::where($condition)->first();
        if (empty($user)) {
            DB::beginTransaction();
            $info = [
                'id'         => 'wechat',
                'robot'      => 0,
                'trial'      => 0,
                'openid'     => $user_info->openid,
                'nickname'   => $user_info->nickname,
                'mobile'     => 'trial',
                'wechat'     => 1,
                'bind'       => 0,
                'avatar'     => $user_info->headimgurl,
                'created_ip' => request()->ip(),
            ];

            $user = User::create($info);
            $user || real()->exception('register.failed.retry');
            $user->createWallet();
            $user->createOption();
            DB::commit();
            if ($state != 'default') {
                UserReference::createReference($user->id, $state);
            }
        }
        request()->offsetSet('is_wechat', true);
        request()->offsetSet('openid', $user_info->openid);
        return $this->login(request());
    }

    private function mobileUpdate(Request $request)
    {
        $this->user->CheckIsTrial();
        $user = $this->user;
        $user->mobile == $request->mobile && real()->exception('新手机号与当前手机号相同');

        $rule = [
            'mobile'   => 'required',
            'ver_code' => 'required',
        ];
        $data = $request->all();
        real()->validator($data, $rule);

        $check = SMS::check('mobileUpdate', $request->mobile, $request->ver_code);
        $check || real()->exception('ver_code.invalid');

        $exist = User::where('mobile', $request->mobile)->where('id', '!=', $user->id)->first();
        $exist && real()->exception('该手机号已被其它用户绑定');

        $user->mobile = $request->mobile;
        $save         = $user->save();
        $save || real()->exception('mobile.reset.failed');

        return real()->success('mobile.reset.success');
    }

    private function nicknameUpdate(Request $request)
    {
        $rule = ['nickname' => 'required|min:1|max:8'];
        $data = $request->all();
        real()->validator($data, $rule);

        $user           = $this->user;
        $user->nickname = $request->nickname;
        $save           = $user->save();

        $save || real()->exception('nickname.update.failed');
        return real($data)->success('nickname.update.success');
    }

    private function realNameUpdate(Request $request)
    {
        $this->user->CheckIsTrial();
        DB::beginTransaction();
        $rule    = ['real_name' => 'required|min:2|max:4|real_name'];
        $data    = $request->all();
        $message = ['real_name.real_name' => '姓名校验失败 仅支持中文姓名'];
        real()->validator($data, $rule, $message);
        $user = $this->user;
        $user->real_name && real()->exception('您已经登记过真实姓名 暂不支持修改');

        $user->real_name = $request->real_name;
        $save            = $user->save();

        $save || real()->exception('real_name.update.failed');

        DB::commit();

        $data['wallet'] = $user->wallet;
        return real($data)->success('real_name.update.success');
    }

    private function safeWordUpdate(Request $request)
    {
        $this->user->CheckIsTrial();
        $rule = [
            'mobile'    => 'required',
            'safe_word' => 'required',
            'ver_code'  => 'required',
        ];

        $data = $request->all();
        real()->validator($data, $rule);

        $check = SMS::check('safeWord', $this->user->mobile, $request->ver_code);
        $check || real()->exception('ver_code.invalid');

        $this->user->safe_word = $request->safe_word;
        $temp                  = $this->user->save();
        $temp || real()->exception('safe_word.reset.failed');

        return real()->success('safe_word.reset.success');
    }
}
