<?php

namespace App\Models\Concerns;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait MilikUser
{
    /** Admin melihat semua data, user hanya data miliknya. */
    public function scopeMilikUser(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user && $user->isSuperAdmin()) {
            return $query;
        }

        return $query->where($this->getTable() . '.user_id', $user?->id);
    }
}