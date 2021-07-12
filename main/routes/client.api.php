<?php

Route::get('option/get', 'OptionController@get');

Route::prefix('auth')->group(function () {
    Route::post('register', 'UserAuthController@register'); //用户注册
    Route::post('login', 'UserAuthController@login'); //用户登录
    Route::post('password', 'UserAuthController@passwordUpdate'); //用户重置密码
    Route::get('nickname', 'UserAuthController@randNickname'); //注册时生成随机昵称
    Route::post('trial', 'UserAuthController@trial'); // 注册试玩账户
    Route::get('robot-add', 'UserAuthController@robotAdd'); // 注册试玩账户
    Route::get('wechat-login', 'UserAuthController@wechatLogin'); // 登录微信用户
    Route::get('get-token', 'UserAuthController@getToken'); // 重定向获取微信用户
});
Route::post('sms/{privateMethod}', 'SMSController@get');

Route::auto('app', 'Client\AppHomeController');
Route::auto('rank', 'Client\RankingController');

Route::full('article/{cat_id}', 'ArticleController');
Route::get('article/{cat_id}/new', 'ArticleController@new');
Route::get('article/get', 'ArticleController@get');
Route::full('page/{type}', 'SinglePageController');
Route::get('agent/partner', 'UserAgentController@partner');

Route::middleware('jwt:users')->group(function () {
    Route::get('lotto/{lotto_name}/united', 'LottoController@united');
    Route::get('lotto/{lotto_name}/config', 'LottoController@config');
    Route::get('lotto/{lotto_name}/open-last', 'LottoController@openLast');
    Route::get('lotto/{lotto_name}/bet-newest', 'LottoController@betNewest');
    Route::post('lotto/{lotto_name}/bet-basic', 'LottoController@betBasic');
    Route::get('lotto/{lotto_name}/open-log', 'LottoController@openLog');
    Route::get('lotto/{lotto_name}/was-bet', 'LottoController@wasBet');
    Route::get('lotto/{lotto_name}/float-odds', 'LottoController@floatOdds');
    Route::get('lotto/{lotto_name}/open-check', 'LottoController@openCheck');
    Route::get('lotto/{lotto_name}/stop-info', 'LottoController@stopInfo');
    Route::get('lotto/{lotto_name}/chat-open', 'LottoController@chatOpen');
    Route::get('lotto/{lotto_name}/fresh-chat', 'LottoController@freshChat');
    Route::get('lotto/{lotto_name}/now-bet', 'LottoController@nowBet');
    Route::get('lotto/{lotto_name}/history-log', 'LottoController@historyLog');
    Route::get('lotto/{lotto_name}/show-item', 'LottoController@showItem');
    Route::post('lotto/{lotto_name}/send-message', 'LottoController@sendMessage');
    Route::get('lotto/{lotto_name}/{play_type}/bet-stats', 'LottoController@betStats');
    Route::post('lotto/{lotto_name}/{play_type}/favorite', 'LottoController@lottoFavorite');
    Route::auto('bet-tools', 'Client\BetToolsController');
    Route::auto('bet-automator', 'Client\BetAutomatorController');

    Route::auto('user', 'Client\UserAuthController');
    Route::post('user/safe-word/check', 'UserAuthController@safeWordCheck');
    Route::get('user/stats', 'UserAuthController@stats');
    Route::auto('wallet', 'Client\UserWalletController');
    Route::auto('offline', 'Client\OfflineLogController');
    Route::auto('agent', 'Client\UserAgentController');
    Route::auto('service', 'Client\ServiceController');
    Route::auto('bank-card', 'Client\BankCardController');
    Route::auto('withdraw', 'Client\WithdrawController');
    Route::auto('recharge', 'Client\RechargeController');
    Route::auto('transfer', 'Client\TransferController');
    Route::auto('statistics', 'Client\StatisticsController');
    Route::auto('user/reference', 'Client\UserReferenceController');
    Route::auto('user/award', 'Client\UserAwardController');
    Route::auto('red-bag', 'Client\RedBagController');

    Route::auto('user/level', 'Client\UserLevelController');
    Route::auto('chat', 'Client\ChatController');

    Route::post('image/create', 'CommonController@imageCreate');
});
