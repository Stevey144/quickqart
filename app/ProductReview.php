<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['rating', 'author', 'content', 'ip', 'email', 'product_id'];
}
