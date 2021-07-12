<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\LottoModule\LottoUtils;

class DataStats
{
    public $date_end = null;

    public $date_start = null;

    public $user_award_group = [
        'recharge_k2',
        'recharge_w5',
        'bet_day_loss',
        'play_ca28',
        'play_de28',
        'play_bit28',
        'play_cw28',
        'play_bj28',
        'play_pc28',
        'ref_1_bet_rebate',
        'ref_5_loss',
        'user_level_day',
        'user_level_upgrade',
    ];

    public $user_id = null;

    public function __construct($date_start, $date_end, $user_id = null)
    {
        $this->date_start = $date_start;
        $this->date_end   = $date_end;
        $this->user_id    = $user_id;
    }

    public function agentAdvance()
    {
        $data = \App\Models\UserAgent::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        // $data->where('status', 1);
        return $data->sum('advance');
    }

    public function bet($effective = null)
    {
        $data = \App\Models\LottoModule\Models\BetLog::query();
        if ($this->user_id) {
            $data->where('user_id', $this->user_id);
        } else {
            $data->where('trial', 0);
        }

        $effective && $data->where('effective', $effective);
        $data->where('status', '!=', 3);
        $data->where('confirmed_at', '>=', $this->date_start);
        $data->where('confirmed_at', '<=', $this->date_end);
        $raw_bet   = DB::raw('SUM(total) as total');
        $raw_bonus = DB::raw('SUM(bonus) as bonus');
        $result    = $data->first([$raw_bet, $raw_bonus]);

        return $result;
    }

    public function dataStatsFix($name)
    {
        $data = \App\Models\DataStatsFix::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        $data->where('name', $name);
        $data->where('date', '>=', $this->date_start);
        $data->where('date', '<=', $this->date_end);

        $raw    = DB::raw('SUM(value) as total');
        $result = $data->first($raw);
        return $result;
    }

    public function lottoStats($lotto_name, $play_type = null)
    {
        $raw_bet        = DB::raw('SUM(total) as total');
        $raw_bonus      = DB::raw('SUM(bonus) as bonus');
        $raw_profit     = DB::raw('(SUM(bonus) - SUM(total)) as profit');
        $raw_effective  = DB::raw('SUM(if(effective=1,total,0)) as effective');
        $raw_date_where = DB::raw('date_format(`confirmed_at`,"%Y-%m-%d")');

        $data = DB::table('bet_logs')->where('lotto_index', 'regexp', $lotto_name);
        $play_type && $data->where('play_type', $play_type);

        if ($this->user_id) {
            $data->where('user_id', $this->user_id);
        } else {
            $data->where('trial', 0);
        }

        $result = $data->where('status', '2')
            ->where('confirmed_at', '>=', $this->date_start)
            ->where('confirmed_at', '<=', $this->date_end)
            ->first([$raw_bet, $raw_bonus, $raw_profit, $raw_effective]);

        return $result;
    }

    public function lottoStatsDetailGroup()
    {
        $items  = LottoUtils::lottoItems();
        $result = [];
        foreach ($items as $item) {
            $lotto_name = $item['name'];
            $play_types = $item['types'];
            foreach ($play_types as $play_type) {
                $temp         = $this->lottoStats($lotto_name, $play_type);
                $key          = $lotto_name . '_' . $play_type . '_' . 'profit';
                $result[$key] = toDecimal($temp->profit);
            }
        }

        return $result;
    }

    public function lottoStatsGroup()
    {
        $items  = LottoUtils::lottoItems();
        $result = [];
        foreach ($items as $item) {
            $lotto_name   = $item['name'];
            $temp         = $this->lottoStats($lotto_name);
            $key          = $lotto_name . '_' . 'profit';
            $result[$key] = toDecimal($temp->profit);
        }

        return $result;
    }

    public function redBag()
    {
        $data = DB::table('red_bags');
        $this->user_id && $data->where('create_id', $this->user_id);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw_total    = DB::raw('SUM(total) as total');
        $raw_received = DB::raw('SUM(received) as received');
        $raw_returned = DB::raw('(SUM(total) - SUM(received)) as returned');
        $result       = $data->first([$raw_total, $raw_received, $raw_returned]);

        return $result;
    }

    public function redBagLog()
    {
        $data = \App\Models\RedBagLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw_total = DB::raw('SUM(amount) as total');
        $result    = $data->first($raw_total);
        return $result;
    }

    public function registerAgentCount()
    {
        $data = \App\Models\UserAgent::where('status', '1')->count();
        return $data;
    }

    public function registerRobotCount()
    {
        $data = \App\Models\User::where('robot', '1')->count();
        return $data;
    }

    public function registerTrialCount()
    {
        $data = \App\Models\User::where('trial', '1')->count();
        return $data;
    }

    public function registerUserCount()
    {
        $data = \App\Models\User::where('robot', '0')->where('trial', '0')->count();
        return $data;
    }

    public function transferAward()
    {
        $data = \App\Models\UserWallet\TransferData::query();
        $data->where('status', 2);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $result = $data->first(
            [
                DB::raw('sum(award_agent_base) as award_agent_base'),
                DB::raw('sum(award_agent_refer) as award_agent_refer'),
                DB::raw('sum(award_user_base) as award_user_base'),
                DB::raw('sum(award_user_first) as award_user_first'),
            ]
        );

        $result->award_agent_base += $this->dataStatsFix('transfer_award_agent_base')->total;
        $result->award_agent_refer += $this->dataStatsFix('transfer_award_agent_refer')->total;
        $result->award_user_base += $this->dataStatsFix('transfer_award_user_base')->total;
        $result->award_user_first += $this->dataStatsFix('transfer_award_user_first')->total;

        return $result;
    }

    public function transferFee()
    {
        $data = \App\Models\UserWallet\Transfer::query();
        $data->where('status', 2);
        $this->user_id && $data->where('drawee_id', $this->user_id);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $result = $data->first(DB::raw('sum(transfer_fee) as total'));

        return $result;
    }

    public function transferType($type)
    {
        $data = \App\Models\UserWallet\Transfer::query();
        $data->where('status', 2);
        $data->where('type', $type);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $result = $data->first(DB::raw('sum(amount) as total'));

        // if ($type === 'user.to.agent') {
        //     $field = 'transfer_user_to_agent';
        // }
        // if ($type === 'agent.to.user') {
        //     $field = 'transfer_agent_to_user';
        // }

        // if ($field) {
        //     $result->total += $this->dataStatsFix($field)->total;
        // }

        return $result;
    }

    public function transferUser($type)
    {
        $data = \App\Models\UserWallet\Transfer::where($type . '_id', $this->user_id);
        $data->where('status', 2);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);

        $result->total += $this->dataStatsFix('transfer_' . $type)->total;
        $field = null;

        return $result;
    }

    public function transferUserAward($field)
    {
        $data = \App\Models\UserWallet\TransferData::query();
        $data->where('status', 2);
        if ($field == 'award_user_base') {
            $data->where('payee_id', $this->user_id);
        } else {
            $data->where('drawee_id', $this->user_id);
        }
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $result = $data->sum($field);
        $result += $this->dataStatsFix('transfer_' . $field)->total;

        return $result;
    }

    public function userAward($type = null)
    {
        $data = \App\Models\UserWallet\WalletLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        // $type && $data->where('type', $type);
        // $data->where('source_name', 'regexp', 'award');
        $data->where(function ($query) {
            $query->where('source_name', 'regexp', 'award')
                ->orwhere('source_name', 'regexp', 'agentrebate')
                ->orwhere('source_name', 'user.bet.rebate');
        });
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        return $result;
    }

    public function userRebate()
    {
        $data = \App\Models\UserWallet\WalletLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);

        $data->where('source_name', 'user.bet.rebate');
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        return $result;
    }

    public function userAgentRebate()
    {
        $data = \App\Models\UserWallet\WalletLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);

        $data->where('source_name', 'regexp', 'agentrebate');
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        return $result;
    }

    public function userRechargeBack()
    {
        $data = \App\Models\UserWallet\WalletLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);

        $data->where('source_name', 'award.recharge.back');
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        return $result;
    }

    public function redBagReceive()
    {
        $data = \App\Models\UserWallet\WalletLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);

        $data->where('source_name', 'award.red_bag.receive');
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        return $result;
    }

    public function userAwardGroup()
    {
        $result = [];
        foreach ($this->user_award_group as $type) {
            $temp         = $this->userAward($type);
            $key          = 'user_award_' . $type;
            $result[$key] = toDecimal($temp->total);
        }

        return $result;
    }

    public function userReference($level = null)
    {
        $data = \App\Models\UserReference::query();
        $level && $data->where('level', $level);
        $this->user_id && $data->where('ref_str', 'regexp', $this->user_id);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $result = $data->count();
        return $result;
    }



    public function wallet()
    {
        $data = \App\Models\UserWallet\Wallet::query();
        $data->where('trial', '!=', '1');
        $data->where('robot', '!=', '1');
        $result = $data->first(
            [
                DB::raw('sum(balance) as balance'),
                DB::raw('sum(bank) as bank'),
                DB::raw('sum(fund) as fund'),
            ]
        );

        return $result;
    }

    public function walletFundInterest()
    {
        $data = \App\Models\UserWallet\WalletLog::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $data->where('source_name', 'fund.settle');
        $data->where('field', 'fund');
        $result = $data->sum('amount');

        return $result;
    }

    public function walletRecharge()
    {
        $data = \App\Models\UserWallet\BalanceRecharge::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        $data->where('status', 2);
        $data->where('confirmed_at', '>=', $this->date_start);
        $data->where('confirmed_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        $fix    = $this->dataStatsFix('wallet_recharge');
        $result->total += $fix->total;
        return $result;
    }

    public function walletSystemService($plus = true)
    {
        $symbol = $plus === true ? '>' : '<';
        $data   = \App\Models\UserWallet\WalletLog::query();
        $data->where('source_name', 'system.service');
        $data->where('amount', $symbol, 0);
        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        return $data->sum('amount');
    }

    public function walletWithdraw()
    {
        $data = \App\Models\UserWallet\BalanceWithdraw::query();
        $this->user_id && $data->where('user_id', $this->user_id);
        $data->where('status', 2);
        $data->where('confirmed_at', '>=', $this->date_start);
        $data->where('confirmed_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        $fix    = $this->dataStatsFix('wallet_withdraw');
        $result->total += $fix->total;
        return $result;
    }

    public function getExpense()
    {
        $data = \App\Models\Expense::query();
        $this->user_id && $data->where('user_id', $this->user_id);


        $data->where('created_at', '>=', $this->date_start);
        $data->where('created_at', '<=', $this->date_end);
        $raw    = DB::raw('SUM(amount) as total');
        $result = $data->first($raw);
        return $result;
    }
}
