<?php

namespace App;

class SuspendedUser extends BaseModel
{
    protected $fillable = ['user_id', 'reason'];

    protected $hidden = ['id', 'user_id'];
}
