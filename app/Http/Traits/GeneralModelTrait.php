<?php
/**
 * Created by PhpStorm.
 * User: gemdajs
 * Date: 1/21/19
 * Time: 11:24 AM
 */

namespace App\Http\Traits;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

trait GeneralModelTrait
{
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        if (!in_array('deleted_at', $this->dates)) $this->dates[] = 'deleted_at';

        foreach (['updated_by', 'created_by', 'deleted_by'] as $a) {
            if (!in_array($a, $this->fillable)) {
                $this->fillable[] = $a;
            }
        }

        foreach (['was_deleted'] as $a) {
            if (!in_array($a, $this->appends)) {
                $this->appends[] = $a;
            }
        }

        foreach (['created_by', 'updated_by', 'deleted_by'] as $a) {
            if (!in_array($a, $this->hidden)) {
                $this->hidden[] = $a;
            }
        }
        parent::__construct($attributes);
    }

    public function hasBeenDeleted()
    {
        return $this->deleted_at !== null;
    }

    public function getWasDeletedAttribute()
    {
        return $this->hasBeenDeleted();
    }


    private function convertDate($date)
    {
        $chunks = explode(' ', $date);
        if (count($chunks) > 5) {
            $date = [
                $chunks[1],
                $chunks[2],
                $chunks[3]
            ];

            return implode(' ', $date);
        }

        return $date;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }

//    public function getCreatedAtAttribute() {
//        $createdAt = $this->attributes['created_at'];
//        return date('d/m/Y H:i', strtotime($createdAt));
//    }

}
