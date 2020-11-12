<?php

namespace App\Http\Controllers;

use App\Enums\EventTypeEnum;
use App\Events\LocalNotifyAdminEvent;
use App\Events\PasswordResetEvent;
use App\Events\UserRegistrationEvent;
use App\StoreVisitor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $subdomain;
    public $isProduction = false;

    public function __construct()
    {
        $this->subdomain = Route::input('subdomain');
        Config::set('request.subdomain', $this->subdomain);

        $store = '';
        if ($this->subdomain) {
            $store = $this->subdomain;
        }

        if ($store) {
            DB::table('stores')->where('slug', $store)->increment('visits');
        }

        StoreVisitor::query()->create(['ip' => request()->ip(),
            'agent' => request()->userAgent(), 'store' => $store]);
    }

    public function withJson(array $arr, $status = 200)
    {
        return response()->json($arr, $status);
    }

    public function withMessage(string $message, $status = 200)
    {
        return $this->withJson(['message' => $message], $status);
    }

    public function permissionDenied(string $message = "You do not have the required permission to perform this action")
    {
        return $this->withJson(['message' => $message], 403);
    }

    public function reportEvent($eventType, ...$args)
    {
        switch ($eventType) {
            case EventTypeEnum::LOGGED_IN:
                $user = $args[0];
                event(new LocalNotifyAdminEvent('User identified with email: ' . $user->email . ', username: ' . $user->username . ' logged in'));
                break;
            case EventTypeEnum::FAILED_LOGIN:
                event(new LocalNotifyAdminEvent('Unknown user from ip address: ' . request()->ip() . ' attempted a failed logged in'));
                break;
            case EventTypeEnum::USER_ACCOUNT_CREATION:
                event(new UserRegistrationEvent($args[0]));
                break;
            case EventTypeEnum::PASSWORD_RESET_REQUEST:
                event(new PasswordResetEvent($args[0], $args[1]));
                break;
        }
    }

    public function getAuthUserStoreFromHeader()
    {
        return auth()->user()->store;
    }
}
