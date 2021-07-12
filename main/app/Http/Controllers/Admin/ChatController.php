<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chat;
use App\Models\RedBag;
use Illuminate\Http\Request;
use App\Packages\Utils\PushEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Models\ModelTrait\ThisUserTrait;
use Illuminate\Support\Facades\Artisan;

class ChatController extends Controller
{
    use ThisUserTrait;

    public function image(Request $request)
    {
        $rule = ['file' => 'required'];
        $data = $request->all();
        real()->validator($data, $rule);

        $image = PushMessage::uploadImage($request->file);

        $data = [
            'to'           => 100000,
            'from'         => $this->user->id,
            'message_type' => 'image',
            'message'      => $image['path'],
            'index'        => 'private',
        ];

        $create          = PushMessage::create($data);
        $create->last_at = strtotime($create->created_at);
        $create->target  = $create->target()->first(['id', 'nickname'])->toArray();

        $result = $create->toArray();
        $push   = PushEvent::name('service')->toUser(100000)->data($result);
        $push   = PushEvent::name('service')->toUser($this->user->id)->data($result);

        return real($result)->success('image.upload.success');
    }

    public function index()
    {
        $items  = Chat::with('user', 'redbag')->orderBy('id', 'desc');
        $result = $items->paginate(15)->toArray();
        return real()->listPage($result)->success();
    }

    public function read()
    {
        $update = ['read' => 1, 'read_at' => date('Y-m-d H:i:s')];
        $temp   = PushMessage::where('to', $this->user->id)->where('from', 100000)->where('read', 0)->update($update);
        return real()->debug($temp)->success();
    }

    public function redBagSend(Request $request)
    {
        $rule    = ['total' => 'required', 'quantity' => 'required', 'type' => 'required'];
        $data    = $request->all();
        $message = ['total.required' => '请填写红包金额', 'quantity.required' => '请填写红包数量', 'type.required'];
        real()->validator($data, $rule, $message);

        DB::beginTransaction();
        //创建红包
        if ($request->expired_at == null) {
            $data['expired_at'] = date('Y-m-d H:i:s', strtotime('+1 hour'));
        }

        $data['quantity']  = $data['quantity'] == 0 ? 1 : $data['quantity'];
        $data['condition'] = empty($data['condition']) ? 'today' : $data['condition'];
        $data['create_id'] = 100000;
        if ($data['type'] == 'turnover') {
            $data['receive_id'] = null;
        }
        $create = RedBag::create($data);

        $chatdata = [
            'user_id'    => 100000,
            'type'       => 2,
            'message'    => $request->type == 'turnover' ? '流水红包' : '指定红包',
            'red_bag_id' => $create->id,
        ];
        $chatcreate = Chat::create($chatdata);
        DB::commit();
        $result                 = Chat::with('user', 'redbag')->orderBy('id', 'desc')->first()->toArray();
        $result['has_received'] = false;
        $push                   = PushEvent::name('message')->toPresence('chat.group')->data($result);


        $online = PushEvent::users('lotto.ca28');

        if (count($online['users']) > 0) {
            foreach ($online['users'] as $key => $val) {

                $message = [
                    'message' => '红包雨来临，请前往聊天室领取',
                    'event'   => 'recharge',
                    'wallet'  => '',
                ];
                $res = PushEvent::name('Toast')->toUser($val['id'])->data($message);
            }
        }

        $online2 = PushEvent::users('lotto.ylde28');

        if (count($online2['users']) > 0) {
            foreach ($online2['users'] as $key => $val) {

                $message = [
                    'message' => '红包雨来临，请前往聊天室领取',
                    'event'   => 'recharge',
                    'wallet'  => '',
                ];
                $res = PushEvent::name('Toast')->toUser($val['id'])->data($message);
            }
        }


        return real($result)->debug($push)->success('message.send.success');
    }

    public function send(Request $request)
    {
        $rule = ['message' => 'required'];

        $data = $request->all();
        real()->validator($data, $rule);
        //dd($this->user->id);
        //dd($request->message);

        $data = [
            'user_id'    => 100000,
            'type'       => $request->type != null ? $request->type : 1,
            'message'    => $request->message,
            'red_bag_id' => $request->red_bag_id != null ? $request->red_bag_id : null,
        ];

        $create = Chat::create($data);
        //$create->last_at = strtotime($create->created_at);
        $result = Chat::with('user', 'redbag')->orderBy('id', 'desc')->first()->toArray();
        $push   = PushEvent::name('message')->toPresence('chat.group')->data($result);

        return real($result)->debug($push)->success('message.send.success');
    }

    public function redBagCancel(Request $request)
    {
        $red_bag = RedBag::find($request->id);
        if (!$red_bag) {
            return real()->exception('参数错误，无红包信息');
        }
        if ($red_bag->received != 0) {
            return real()->exception('无法撤回，该红包已被领取');
        }


        $result = $red_bag->delete();
        $result1 = Chat::destroy($request->chat_id);
        return real($result)->success('撤回成功');
    }

    public function robotReceive(Request $request)
    {
        $exitCode = Artisan::call('robt:receive-red-bag');
        return real()->success('执行成功，请隔10秒后再点击');
    }
}
