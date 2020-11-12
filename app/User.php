<?php

namespace App;

use App\Http\Traits\ForeignWalletTrait;
use App\Http\Traits\GeneralModelTrait;
use App\Http\Traits\LocalWalletTrait;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberFormat;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract,
    JWTSubject, MustVerifyEmail,
    \Illuminate\Contracts\Auth\CanResetPassword
{
    use Authenticatable, Authorizable, HasRoles;
    use GeneralModelTrait, LocalWalletTrait, ForeignWalletTrait;
    use Notifiable;
    use \Illuminate\Auth\MustVerifyEmail;
    use CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'gender',
        'phone_number',
        'password',
        'upload',
        'contact',
        'email_verified_at',
        'activation_code',
        'password_reset_code'
    ];

    protected $appends = ['role_names', 'slug', 'is_suspended', 'is_self'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public static function convertPhoneNumberToInternationalStandard($phone)
    {
        try {
            $formatted = PhoneNumber::make($phone)->formatInternational();
        } catch (\Exception $exception) {
            $formatted = PhoneNumber::make($phone)->ofCountry('NG')->formatInternational();
        }
        return $formatted;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getRoleNamesAttribute()
    {
        return $this->roles()->pluck('name');
    }

    public function getSlugAttribute()
    {
        return $this->username;
    }

    public function getContactAttribute()
    {
        $contact = $this->attributes['contact'];

        if ($contact) {
            try {
                return json_decode($contact);
            } catch (\Exception $exception){}
        }

        return [
            'state' => '',
            'country' => '',
            'address' => ''
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function localNotify($message)
    {
        Notification::query()->create(['user_id' => $this->id, 'message' => $message, 'status' => 0]);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function getStoreAttribute(){
        $storeUrl = request()->header('PREF-STORE');
//        Log::alert("Store Url: " . $storeUrl);
        return Store::query()->where('slug', $storeUrl)->where('user_id', $this->id)->first();
    }

    public function suspension()
    {
        return $this->hasOne(SuspendedUser::class, 'user_id')->latest();
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'user_id');
    }

    public function getIsSelfAttribute()
    {
        return auth()->check() && auth()->id() === $this->id;
    }

     public function getIsSuspendedAttribute()
    {
        return $this->suspension()->count() > 0;
    }

    public function getClaim()
    {
        $roles = collect([]);
        $permissions = collect([]);
        foreach ($this->roles as $r) {
            $roles->push($r->name);
            $permissions = $permissions->merge($r->permissions()->pluck('name'));
        }
        return [
            "email" => $this->email,
            "permission" => $permissions,
            "role" => $roles,
            "name" => $this->name,
            'upload' => $this->upload,
            'id' => $this->id,
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function can($ability, $arguments = [])
    {
        try {
            return $this->hasPermissionTo($ability, 'api') || $this->hasPermissionTo($ability, 'web');
        } catch (\Exception $e) {
            return false;
        }
    }

    public function routeNotificationForSmsApi() {
        return preg_replace('/[^0-9]/','', $this->phone_number); //Name of the field to be used as mobile
    }

}
