<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'message', 'status'];

    public function scopeUnread($query) {
        return $query->where('status', 0);
    }
}
