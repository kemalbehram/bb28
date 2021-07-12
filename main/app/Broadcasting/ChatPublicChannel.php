<?php
namespace App\Broadcasting;

use App\Models\User;

class ChatPublicChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function join(User $user, $id)
    {

        return ['id' => $user->id, 'nickname' => $user->nickname];
    }

}
