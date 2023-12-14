<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder
{
    use FiltersQuery;

    private function filterRules(): array
    {
        return $rules = [
            'search' => 'filled',
            'state' => 'in:active,inactive',
            'role' => 'in:admin,user',
        ];
    }

    public function findByEmail($email)
    {
        return static::where('email', $email)->first();
    }

    public function filterBySearch($search)
    {
        return $this->where(function ($query) use ($search) {
            $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('team', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
        });
    }

    public function filterByState($state)
    {
        return $this->where('active', $state == 'active');
    }
}