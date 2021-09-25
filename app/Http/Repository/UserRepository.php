<?php


namespace App\Http\Repository;


use App\Models\User;

class UserRepository
{
    public function getUserByIdForRole($auth_id)
    {
        return User::find($auth_id);
    }
}
