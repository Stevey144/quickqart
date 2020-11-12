<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageVariable extends Model
{
    protected $fillable = ['message_id', 'variable_id'];

    public function message() {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public function variable() {
        return $this->belongsTo(Variable::class, 'variable_id');
    }
}
