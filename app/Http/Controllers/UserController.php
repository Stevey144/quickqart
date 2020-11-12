<?php


namespace App\Http\Controllers;

use App\Enums\EventTypeEnum;
use App\Events\LocalNotifyAdminEvent;
use App\Notifications\GeneralNotification;
use App\Notifications\SuspensionNotification;
use App\Permission;
use App\Role;
use App\Store;
use App\SuspendedUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index(Request $request)
    {

        if (!auth()->user()->can(Permission::MANAGE_USERS)) {
            return $this->permissionDenied();
        }

        $filter = $request->input('filter');
        $records = User::query()->where('id', '!=', auth()->id())->where(function ($query) use ($filter) {
            $query->where('name', 'like', "%$filter%")
                ->orWhere('email', 'like', "%$filter%")
                ->orWhere('phone_number', 'like', "%$filter%");
        })->with('creator', 'upload');

        if (!auth()->user()->hasRole(Role::SYSADMIN)) {
            $records->whereHas('roles', function ($q) {
                $q->where('name', '!=', Role::SYSADMIN);
            });
        }

        if (($sortBy = $request->get('sort_by')) && ($dir = $request->get('sort_dir'))) {
            if ($sortBy === 'user_type') {
                $records->whereHas('roles', function ($q) use ($dir) {
                    $q->orderBy('name', $dir);
                });
            } else {
                $records->orderBy($sortBy, $dir);
            }
        }

        $records = $records->with('suspension')->paginate();

        $records->appends($request->all());

        return $this->withJson(['data' => $records]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can(Permission::MANAGE_USERS)) {
            return $this->permissionDenied();
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'nullable|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'phone_number' => 'nullable|unique:users',
            'gender' => 'nullable',
            'contact' => 'required|array',
            'role' => 'required|exists:roles,name'
        ]);

        $role = Role::query()->where('name', $request->role)->first();
        if (!$role) {
            return $this->withMessage('Role not found.', 500);
        }

        if (!auth()->user()->hasRole(Role::SYSADMIN) && $role->name === Role::SYSADMIN) {
            return $this->permissionDenied("You do not have permission to create user of this type");
        }

        $data = $request->only(['name', 'username', 'username', 'email', 'gender', 'upload']);
        if ($request->has('phone_number') && ($phone = $request->phone_number)) {
            try {
                $data['phone_number'] = User::convertPhoneNumberToInternationalStandard($phone);

                if (User::query()->where('phone_number', $data['phone_number'])->count()) {
                    return $this->withMessage("Phone number has been taken", 400);
                }
            } catch (\Exception $e) {
                return $this->withMessage("Phone number is not valid", 400);
            }
        } else {
            $data['phone_number'] = '';
        }
        if (!$request->has('username') || !$request->username) {
            $data['username'] = $this->getUserName();
        }
        $data['contact'] = json_encode($request->contact);
        $data['password'] = $request->get('password');

        $user = new User($data);
        if (!$user->save()) {
            return $this->withMessage('Unable to create user account, try again.', 500);
        }

        $user->roles()->attach($role);

        $this->reportEvent(EventTypeEnum::USER_ACCOUNT_CREATION, $user);
        return $this->withJson([
            'message' => 'An activation email has been sent to <strong>' . $user->email . '</strong>.',
        ]);
    }

    public function update(Request $request, $subdomain, $username = null)
    {
        if (!$username || $username === 'me') {
            $user = auth()->user();
        } else {
            $user = User::query()->where('username', $username)->orWhere('id', $username)->first();
        }
        if (!$user) {
            return $this->withMessage('User not found', 404);
        }

        $authUser = auth()->user();

        $isSelf = $authUser->id == $user->id;

        if ($authUser->id !== $user->id && !$authUser->can(Permission::MANAGE_USERS)) {
            return $this->withMessage('You have no privilege to perform this action', 400);
        }

        if (!$isSelf && $user->hasRole(Role::SYSADMIN) && !$authUser->hasRole(Role::SYSADMIN)) {
            return $this->permissionDenied("You do not have permission to update record for user of this type");
        }

        $validationData = [
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'username' => 'nullable|unique:users,username,' . $user->id,
            'phone_number' => 'nullable',
            'gender' => 'nullable',
            'contact' => 'required|array',
        ];

        if (!$request->get('username') && !$request->get('phone_number')) {
            return $this->withMessage("An email or phone number is required", 400);
        }

        if ($request->has('phone_number') && ($phone = $request->phone_number)) {
            try {
                $data['phone_number'] = User::convertPhoneNumberToInternationalStandard($phone);

                if (User::query()->where('phone_number', $data['phone_number'])
                    ->where('id', '!=', $user->id)->count()) {
                    return $this->withMessage("Phone number has been taken", 400);
                }
            } catch (\Exception $e) {
                return $this->withMessage("Phone number is not valid", 400);
            }
        } else {
            $data['phone_number'] = '';
        }

        if (!$request->has('username') || !$request->username) {
            $data['username'] = $this->getUserName();
        }

        $willChangePassword = false;

        if (($willChangePassword = ($request->has('password') && $request->password != ''))) {
            $validationData['password'] = 'required|confirmed|min:6';
        }

        if ($willChangePassword && !$isSelf && !$authUser->can(Permission::CHANGE_OTHERS_PASSWORD)) {
            return $this->permissionDenied("You do not have the permission to change others password");
        }

        $willUpdateRole = false;
        if (!$user->hasRole(Role::SHOP_OWNER) && $request->has('role') && $request->role) {
            $validationData['username'] = 'required|unique:users,username,' . $user->id;
            $validationData['role'] = 'required|exists:roles,name';
            $willUpdateRole = true;
        }

        $this->validate($request, $validationData);

        if ($willUpdateRole) {
            $role = Role::query()->where('name', $request->role)->first();
            if (!$role) {
                return $this->withMessage('Role not found.', 500);
            }
        }

        $data = $request->only(['name', 'username', 'phone_number', 'email', 'gender', 'upload']);

//        if ($willUpdateRole) {
//            $data['username'] = $request->get('username');
//        }

        $data['contact'] = json_encode($request->contact);

        if (!$user->update($data)) {
            return $this->withMessage('Unable to update user record, try again.', 500);
        }

        if ($willUpdateRole) {
            $user->syncRoles($role);
        }

        if (($isSelf || $authUser->can(Permission::CHANGE_OTHERS_PASSWORD)) && $willChangePassword) {
            $user->password = $request->password;
            if ($user->save()) {
                return $this->withJson([
                    'message' => "Details updated with password",
                ]);
            }
        }

        return $this->withMessage('Record updated successfully');
    }

    public function destroy($username)
    {
        if (!auth()->user()->can(Permission::MANAGE_USERS)) {
            return $this->permissionDenied();
        }

        $record = User::query()->where('username', $username)->orWhere('id', $username)->first();

        if (!$record) {
            return $this->withMessage('Record not found', 404);
        }

        if ($record->is_self) {
            return $this->withMessage('You cannot remove your account', 500);
        }

        if ($record->is_default) {
            return $this->withMessage('This account cannot be deleted.', 500);
        }

        if (!auth()->user()->hasRole(Role::SYSADMIN) && $record->hasRole(Role::SYSADMIN)) {
            return $this->permissionDenied("You do not have permission to create user of this type");
        }

        if ($record->delete()) {
            Store::query()->where('user_id', $record->id)->delete();
            return $this->withMessage('Record has been deleted.');
        }

        return $this->withMessage('unable to delete record, try again', 500);
    }

    private function getUserName($length = 10)
    {
        $username = Str::random($length);
        if (User::query()->where('username', $username)->count()) {
            return $this->getUserName($length + 2);
        }

        return $username;
    }

    public function profile(Request $request, $subdomain, $username = null)
    {
        if (!$username || $username === 'me') {
            $user = auth()->user();
        } else {
            $user = User::query()->where('username', $username)->orWhere('id', $username)->first();
        }
        if (!$user) {
            return $this->withMessage('User not found', 404);
        }

        $authUser = auth()->user();

        if ($authUser->id !== $user->id && !$authUser->can(Permission::MANAGE_USERS)) {
            return $this->withMessage('You have no privilege to perform this action', 400);
        }

        if ($request->has('with-stores')) {
            $user->load('stores');
        }

        if (!$user->is_self && $user->hasRole(Role::SYSADMIN) && !$authUser->hasRole(Role::SYSADMIN)) {
            return $this->permissionDenied("You do not have permission to edit the user of this type");
        }

        return $this->withJson(['data' => $user]);
    }

    public function suspendAccount(Request $request, $username)
    {
        $this->validate($request, [
            'reason' => 'required'
        ]);

        $user = User::query()->where('username', $username)->orWhere('id', $username)->first();
        if (!$user) {
            return $this->withMessage('User not found', 404);
        }

        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return $this->withMessage('You cannot suspend own account', 400);
        }

        if (!$authUser->can(Permission::MANAGE_USERS)) {
            return $this->withMessage('You have no privilege to perform this action', 400);
        }

        if ($user->hasRole(Role::SYSADMIN) && !$authUser->hasRole(Role::SYSADMIN)) {
            return $this->permissionDenied("You do not have permission to create user of this type");
        }

        $reason = $request->reason;

        SuspendedUser::query()->create([
            'user_id' => $user->id,
            'reason' => $reason,
        ]);

        try {
            $user->notify(new SuspensionNotification($user, $reason));
        } catch (\Exception $e) {
        }

        $role = $user->role_names->join(', ');

        event(new LocalNotifyAdminEvent("$user->name's (identified by email: $user->email, role: $role) account has been suspended by $authUser->name. The reason given is: $reason"));

        return $this->withJson(['data' => $user]);
    }

    public function reactivateAccount(Request $request, $username)
    {
        $user = User::query()->where('username', $username)->orWhere('id', $username)->first();
        if (!$user) {
            return $this->withMessage('User not found', 404);
        }

        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return $this->withMessage('You cannot suspend/reactivate own account', 400);
        }

        if (!$authUser->can(Permission::MANAGE_USERS)) {
            return $this->withMessage('You have no privilege to perform this action', 400);
        }

        if (!$user->is_suspended) {
            return $this->withMessage('User was not suspended', 400);
        }

        if (!$user->suspension()->delete()) {
            return $this->withMessage('Unable to complete  was not suspended', 400);
        }

        try {
            $user->notify(new GeneralNotification("Your account has been reactivated. Kindly login using the action link below. ", route('login'), "Account Reactivated"));
        } catch (\Exception $e) {
        }

        $role = $user->role_names->join(', ');

        event(new LocalNotifyAdminEvent("$user->name's (identified by email: $user->email, role: $role) account has been reactivated by $authUser->name."));

        return $this->withJson(['data' => $user]);
    }

}
