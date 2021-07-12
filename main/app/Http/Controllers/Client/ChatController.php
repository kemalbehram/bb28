<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\RedBagLog;
use App\Models\ModelTrait\ThisUserTrait;
use App\Packages\Utils\PushEvent;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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
            'to' => 100000,
            'from' => $this->user->id,
            'message_type' => 'image',
            'message' => $image['path'],
            'index' => 'private',
        ];

        $create = PushMessage::create($data);
        $create->last_at = strtotime($create->created_at);
        $create->target = $create->target()->first(['id', 'nickname'])->toArray();

        $result = $create->toArray();
        $push = PushEvent::name('service')->toUser(100000)->data($result);
        $push = PushEvent::name('service')->toUser($this->user->id)->data($result);

        return real($result)->success('image.upload.success');
    }

    public function index()
    {

        $items = Chat::with('user', 'redbag')->orderBy('id', 'desc');
        $result = $items->paginate(80)->toArray();
        foreach ($result['data'] as &$value) {
            if (!empty($value['redbag'])) {
                $recived               = RedBagLog::where('parent_id', $value['redbag']['id'])->where('user_id', $this->user->id)->first();
                $value['has_received'] = empty($recived) ? false : true;
            }
        }
        return real()->listPage($result)->success();
    }

    public function read()
    {
        $update = ['read' => 1, 'read_at' => date('Y-m-d H:i:s')];
        $temp = PushMessage::where('to', $this->user->id)->where('from', 100000)->where('read', 0)->update($update);
        return real()->debug($temp)->success();
    }

    public function send(Request $request)
    {

        $rule = ['message' => 'required'];
        $data = $request->all();
        real()->validator($data, $rule);
        //dd($this->user->id);
        //dd($request->message);
        $data = [
            'user_id' => $this->user->id,
            'type' => 1,
            'message' => $request->message,
        ];

        $create = Chat::create($data);
        $create->last_at = strtotime($create->created_at);
        $result = Chat::with('user', 'redbag')->orderBy('id', 'desc')->first()->toArray();
        $push = PushEvent::name('message')->toPresence('chat.group')->data($result);

        return real($result)->debug($push)->success('message.send.success');
    }
}
