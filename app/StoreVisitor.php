<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreVisitor extends Model
{
    protected $fillable = ['ip', 'agent', 'store'];
}
