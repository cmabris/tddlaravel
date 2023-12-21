<?php

namespace App;

use Illuminate\Support\Facades\DB;

class QueryBuilder extends \Illuminate\Database\Eloquent\Builder
{
    public function whereQuery($subquery, $operator, $value = null)
    {
        $this->addBinding($subquery->getBindings());
        $this->where(DB::raw("({$subquery->toSql()})"), $operator, $value);

        return $this;
    }

    public function onlyTrashedif($value)
    {
        if ($value) {
            $this->onlyTrashed();
        }

        return $this;
    }
}