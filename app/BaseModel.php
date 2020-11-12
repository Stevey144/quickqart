<?php
namespace App;

use App\Http\Traits\GeneralModelTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use GeneralModelTrait;
}
