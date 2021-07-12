<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

use App\Broadcasting\ChatPublicChannel;
use App\Broadcasting\LottoPublicChannel;
use App\Broadcasting\UserPrivateChannel;

Broadcast::channel('user.{id}', UserPrivateChannel::class, ['guards' => 'users']);
Broadcast::channel('lotto.{name}', LottoPublicChannel::class, ['guards' => 'users']);
Broadcast::channel('chat.{name}', ChatPublicChannel::class, ['guards' => 'users']);
