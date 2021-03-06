<?php

namespace App\Http\Controllers\Client;

use App\Models\BankCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ModelTrait\ThisUserTrait;

class BankCardController extends Controller
{
    use ThisUserTrait;

    public function check(Request $request)
    {
        // $this->user->CheckIsAgent();
        $this->baseValidator($request);
        $card   = trimAll($request->bank_card);
        $result = $this->checkCard($card);
        return real($result)->success('银行卡校验成功');

    }

    public function create(Request $request)
    {
        // $this->user->CheckIsAgent();

        $extend = ['real_name' => 'required'];
        $this->baseValidator($request, $extend);

        $card = trimAll($request->bank_card);
        $info = $this->checkCard($card);

        $data = [
            'user_id'   => $this->user->id,
            'card'      => $card,
            'card_type' => $request->type_name,
            'code'      => $info['bank_code'],
            'real_name' => $request->real_name,
        ];
        $item = BankCard::create($data);
        $item || real()->exception('bank_card.create.failed.retry');
        $card   = $this->user->bankCard;
        $result = ['items' => $card->makeHidden(['created_at', 'updated_at', 'user_id'])->toArray()];
        return real($result)->success('bank_card.create.success');
    }

    public function delete(Request $request)
    {
        // $this->user->CheckIsAgent();
        $rule = ['id' => 'required|int'];
        $data = $request->all();
        real()->validator($data, $rule);

        $item = BankCard::where('user_id', $this->user->id)->where('id', $request->id)->first();
        $item || real()->exception('data.notexist');
        $item->delete();
        $item->trashed() || real()->exception('data.delete.fail.retry');

        $card   = $this->user->bankCard;
        $result = ['items' => $card->makeHidden(['created_at', 'updated_at', 'user_id'])->toArray()];
        return real($result)->success('bank_card.delete.success');
    }

    public function index(Request $request)
    {
        $this->user->CheckIsTrial();
        // sleep(2);
        // real()->exception('模拟错误');
        $card = $this->user->bankCard;

        $result = ['items' => $card != null ? $card->makeHidden(['created_at', 'updated_at', 'user_id'])->toArray() : null];
        return real($result)->success();
    }

    public function update(Request $request)
    {
        // $this->user->CheckIsAgent();

        $extend = ['real_name' => 'required'];
        $this->baseValidator($request, $extend);

        $card = trimAll($request->bank_card);
        $info = $this->checkCard($card);

        $bankInfo = BankCard::where('user_id', $this->user->id)->first();
        $bankInfo || real()->exception('该卡不存在');

        $bankInfo->real_name = $request->real_name;
        $bankInfo->card      = $card;
        $bankInfo->card_type = $request->type_name;
        $bankInfo->code      = $info['bank_code'];
        $item                = $bankInfo->save();
        $item || real()->exception('bank_card.update.failed.retry');
        $card   = $this->user->bankCard;
        $result = ['items' => $card->makeHidden(['created_at', 'updated_at', 'user_id'])->toArray()];
        return real($result)->success('bank_card.update.success');
    }

    private function baseValidator(Request $request, $extend = [])
    {
        $rule = ['bank_card' => 'required'];
        $rule = array_merge($extend, $rule);
        $data = $request->all();
        return real()->validator($data, $rule);
    }

    private function checkCard($card)
    {
        BankCard::where('user_id', $this->user->id)->where('card', $card)
            ->first() && real()->exception('您已绑定该银行卡');
        $result = BankCard::check($card);
        $result || real()->exception('银行卡校验失败，请仔细核对银行卡信息');
        return $result;
    }
}
