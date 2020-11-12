<?php

namespace App\Http\Controllers;

use App\Notifications\StoreSetupCompletedNotification;
use App\Role;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    public function index(Request $request) {

        $filter = $request->input('filter');
        $stores = Store::query()->with('user')
            ->where('name', 'like', "%$filter%");

        if (($sortBy = $request->get('sort_by')) && ($dir = $request->get('sort_dir'))) {
            if($sortBy === 'owner') {
                $stores->whereHas('user', function ($q) use ($dir) {
                   $q->orderBy('name', $dir);
                });
            } else if($sortBy === 'products') {
//                $stores->raw("select stores.*,  COUNT(products.store) as storecount from products left join stores on stores.slug = products.store group by stores.id order by storecount $dir");
            } else {
                $stores->orderBy($sortBy, $dir);
            }
        }

        $stores = $stores->withDates()->paginate();

        $stores->appends($request->all());

        return $this->withJson(['data' => $stores]);
    }

    public function ownerIndex(Request $request) {

        $filter = $request->input('filter');
        $stores = Store::query()->with('user')
            ->where('name', 'like', "%$filter%")
            ->where('user_id', auth()->id())
            ->withDates()->paginate();

        $stores->appends($request->all());

        return $this->withJson(['data' => $stores]);
    }

    public function show(Request $request, $subdomain, $slug) {
        $store = Store::query()->where('user_id', auth()->id())->where('slug', $slug)->first();

        if (!$store) {
            return $this->withMessage('No store found for current user', 404);
        }
        return $this->withJson(['data' => $store]);
    }

    public function ownerUpdate(Request $request, $subdomain, $slug) {
        if ($slug === 'new') {
            return $this->ownerCreate($request);
        }
        $store = Store::query()->where('user_id', auth()->id())->where('slug', $slug)->first();

        if (!$store) {
            return $this->withMessage('No stores found for user', 404);
        }
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'contact' => 'nullable|array',
            'address' => 'nullable|array',
            'logo' => 'nullable',
            'mobile_logo' => 'nullable',
            'banner' => 'nullable',
            'social' => 'nullable|array',
            'currency' => 'nullable|array'
        ]);

        $data = $request->only('name', 'description', 'mobile_logo', 'logo', 'banner');
        $data['currency'] = json_encode($request->get('currency'));
        $data['contact'] = json_encode($request->get('contact'));
        $data['address'] = json_encode($request->get('address'));
        $data['social'] = json_encode($request->get('social'));

        if (!$store->update($data)) {
            return $this->withMessage('Unable to complete setup, please try again.', 500);
        }

        return $this->withJson([
            'message' => "You online store has been updated successfully.",
            'data' => $store,
            'stores' => auth()->user()->stores()->get()
        ]);
    }

    public function ownerCreate(Request $request) {
        if (auth()->user()->stores()->count() >= 3) {
            return $this->withMessage("Sorry, you can only create a maximum of three (3) stores.", 400);
        }

        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|unique:stores,slug',
            'description' => 'required',
            'contact' => 'nullable|array',
            'address' => 'nullable|array',
            'logo' => 'nullable',
            'mobile_logo' => 'nullable',
            'banner' => 'nullable',
            'social' => 'nullable|array',
            'currency' => 'nullable|array'
        ], [
            'slug.unique' => 'The Store ID has been taken.'
        ]);

        $data = $request->only('slug', 'name', 'description', 'mobile_logo', 'logo', 'banner');
        $data['currency'] = json_encode($request->get('currency'));
        $data['contact'] = json_encode($request->get('contact'));
        $data['address'] = json_encode($request->get('address'));
        $data['social'] = json_encode($request->get('social'));
        $data['user_id'] = auth()->id();

        if (!($store = Store::query()->create($data))) {
            return $this->withMessage('Unable to create your new online store, please try again.', 500);
        }

        $storeLinkO = "https://" . $store->slug . ".quickqart.com";

        try {
            $user = $store->user;
            $user->notify(new StoreSetupCompletedNotification($store, true));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        $users = User::query()->whereHas('roles', function ($query) {
            $query->where('name', Role::SYSADMIN);
        })->get();
        try {
            foreach ($users as $admin) {
                $admin->notify(new StoreSetupCompletedNotification($store, false));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->withJson([
            'message' => "Yay! Your store has been created successfully. Here's a link to you store.<br>$storeLinkO.<br>Please copy and share this link with your prospective customers.",
            'data' => $store,
            'stores' => auth()->user()->stores()->get()
        ]);
    }

    public function destroy(Request $request, $subdomain, $slug) {
        $store = Store::query()->where('slug', $slug)->first();

        if (!$store) {
            return $this->withMessage('Store record not found', 404);
        }

        if ($store->delete()) {
            return $this->withMessage('Store deleted successfully');
        }

        return $this->withMessage('Unable to complete, try again', 500);
    }
}
