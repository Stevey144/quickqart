<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Log;

class ShopScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $store = '';
        if (config()->has('request.subdomain') && ($subdomain = config()->get('request.subdomain'))
            && !in_array($subdomain, ['control-panel', 'setup', 'store'])
        ) {
            $store = $subdomain;
        }

//        Log::alert("Store: $store");

        if ($store) {
            $builder->where(function ($query) use ($store) {
                $query->where('store', $store);
            });
        }
    }
}
