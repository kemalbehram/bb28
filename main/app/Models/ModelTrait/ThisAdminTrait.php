<?php
namespace App\Models\ModelTrait;

trait ThisAdminTrait
{
    protected $user = null;

    public function __construct()
    {
        $user                 = auth('admin')->user();
        $user && $this->user  = $user;
        $user && $this->admin = $user;
    }

    public function checkPassword()
    {
    }
}
