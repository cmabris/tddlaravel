<?php

namespace App;

class UserQuery extends QueryBuilder
{
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}