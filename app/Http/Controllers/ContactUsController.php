<?php

namespace App\Http\Controllers;

use App\Notifications\ContactUsNotification;
use App\Store;
use App\User;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function contact(Request $request) {
        $this->validate($request, [
           'name' => 'required',
           'email' => 'required|email',
           'subject' => 'required',
           'message' => 'required',
        ]);

        $email = '';

        if ($this->subdomain) {
            $user = User::query()->whereHas('stores', function ($query) {
               $query->where('slug', $this->subdomain);
            })->first();

            if ($user) {
                $email = $user->email;
            }
        }

        try {
            (new User(['email' => $email ? $email : env('MAIL_CONTACT_US_ADDRESS')]))
                ->notify(new ContactUsNotification($request->email, $request->name, $request->subject, $request->message));

            $message = 'Your contact message has been received. Expect a feed back from us soon. Thank you.';
            if ($request->expectsJson()) {
                return $this->withMessage($message);
            }

            return back()->with('status', $message);
        } catch (\Exception $exception) {
            $message  = "Unable to complete. Try again in a bit.";
            if ($request->expectsJson()) {
                return $this->withMessage($message, 500);
            }

            return back()->withInput()->with('error', $message);
        }

    }

    public function contactUs() {
        return view('parent.contact');
    }
}
