<?php
Route::post('auth/login', 'AuthController@login'); //管理员登录
Route::get('config/get', 'ConfigController@get');
Route::get('config/mapping', 'ConfigController@mapping');
Route::auto('command', 'Admin\CommandController');

Route::middleware('jwt:admin')->group(function () {
    Route::auto('lotto-stats', 'Admin\LottoStatsController');
    Route::auto('risk', 'Admin\RiskController');
    Route::auto('stats-fix', 'Admin\DataStatsFixController');
    Route::auto('admin-opt', 'Admin\AdminOptController');
    Route::auto('transfer', 'Admin\TransferController');
    Route::full('auth', 'AuthController');
    Route::full('administrator', 'AdministratorController');
    Route::full('article/{cat_id}', 'ArticleController');
    Route::full('article/cat', 'ArticleCategoryController');
    Route::full('notice', 'NoticeController');
    Route::auto('member', 'Admin\MemberController');
    Route::get('member/wallet-log', 'MemberController@WalletLog');
    Route::get('member/transfer-log', 'MemberController@TransferLog');
    Route::get('member/next-level', 'MemberController@nextLevel');
    Route::get('member/check-ip', 'MemberController@checkIP');
    Route::get('member/data-stats', 'MemberController@dataStats');
    Route::post('member/id-update', 'MemberController@idUpdate');
    Route::post('member/username-update', 'MemberController@updateUsername');
    Route::post('member/wallet/update', 'MemberController@walletUpdate');
    Route::post('member/wallet/bank-action', 'MemberController@walletBankAction');
    Route::post('member/wallet/transfer', 'MemberController@walletTransfer');
    Route::post('member/agent/update', 'MemberController@agentUpdate');
    Route::post('image/create', 'CommonController@imageCreate');

    Route::full('option/focus', 'OptionFocusController');
    Route::full('option', 'OptionController');
    Route::post('option/update/patch', 'OptionController@updatePatch');
    Route::get('option/aisles', 'OptionController@aisles');
    Route::post('option/aislesUpdate', 'OptionController@aislesUpdate');
    Route::full('contact', 'ContactMessageController');
    Route::full('single-page/{type}', 'SinglePageController');

    Route::full('lotto/{lotto_name}/config', 'LottoConfigController');
    Route::full('lotto/{room_name}/room', 'LottoRoomController');
    Route::full('lotto/{lotto_name}/data', 'LottoDataController');
    Route::post('lotto/{lotto_name}/data/control', 'LottoDataController@control');
    Route::auto('lotto/bet-log', 'Admin\BetLogController');
    Route::auto('lotto/warning', 'Admin\LottoWarningController');
    Route::auto('service', 'Admin\ServiceController');
    Route::auto('recharge', 'Admin\RechargeController');
    Route::auto('withdraw', 'Admin\WithdrawController');
    Route::auto('data-stats', 'Admin\DataStatsController');
    Route::auto('red-bag', 'Admin\RedBagController');
    Route::auto('user-award', 'Admin\UserAwardController');
    //用户返水
    Route::auto('user-rebate', 'Admin\RebateController');
    Route::get('turnover-rebate/{room_name}/get', 'TurnOverRebateController@get');
    Route::auto('chat', 'Admin\ChatController');
    Route::auto('room', 'Admin\RoomController');

    Route::auto('home', 'Admin\HomeController');
    Route::get('admin-log/index', 'AdminLogController@index');
});
