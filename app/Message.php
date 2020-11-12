<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['slug', 'name', 'description'];

    public function messageTemplates()
    {
        return $this->hasMany(MessageTemplate::class, 'message_id');
    }

    public function variables()
    {
        return $this->hasManyThrough(Variable::class, MessageVariable::class, 'message_id', 'id');
    }
}
