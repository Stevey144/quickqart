<?php
namespace App;

use App\Scopes\ShopScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreBaseModel extends Model
{

    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        if (!in_array('deleted_at', $this->dates)) $this->dates[] = 'deleted_at';

        foreach (['store', 'updated_by', 'created_by', 'deleted_by'] as $a) {
            if (!in_array($a, $this->fillable)) {
                $this->fillable[] = $a;
            }
        }

        foreach ([] as $a) {
            if (!in_array($a, $this->appends)) {
                $this->appends[] = $a;
            }
        }

        foreach (['store', 'created_by', 'updated_by', 'deleted_by'] as $a) {
            if (!in_array($a, $this->hidden)) {
                $this->hidden[] = $a;
            }
        }
        parent::__construct($attributes);
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

    public function scopeOwned($query)
    {
        if (auth()->check()) {
            $store = auth()->user()->store;
            return $query->where(function ($q) use ($store) {
                $q->where('created_by', auth()->id())
                    ->where('store', $store->slug);
            });
        }
        return $query;
    }

    public function scopeWithDates($query)
    {
        if (request()->has('from') && ($from = request()->input('from'))) {
            $from = $this->convertDate($from);
            $fromDate = date('Y-m-d', strtotime($from)) . ' 00:00:00';
            $query = $query->where('created_at', '>=', $fromDate);
        }
        if (request()->has('to') && ($from = request()->input('to'))) {
            $from = $this->convertDate($from);
            $toDate = date('Y-m-d', strtotime($from)) . ' 23:59:59';
            $query = $query->where('created_at', '<=', $toDate);
        }

        return $query;
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ShopScope);

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

    private function convertDate($date) {
        $chunks = explode(' ', $date) ;
        if(count($chunks) > 5) {
            $date = [
                $chunks[1],
                $chunks[2],
                $chunks[3]
            ];

            return implode(' ', $date);
        }

        return $date;
    }
}
