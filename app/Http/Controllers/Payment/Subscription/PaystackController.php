<?php

namespace App\Http\Controllers\Payment\Subscription;

use App\{
    Models\Subscription,
    Classes\GeniusMailer,
    Models\Generalsetting,
    Models\UserSubscription
};

use Carbon\Carbon;
use Illuminate\Http\Request;

class PaystackController extends SubscriptionBaseController
{

    public function store(Request $request)
    {

        $this->validate($request, [
            'shop_name'   => 'unique:users',
        ], [
            'shop_name.unique' => __('This shop name has already been taken.')
        ]);
        $user = $this->user;
        $package = $user->subscribes()->where('status', 1)->orderBy('id', 'desc')->first();
        $subs = Subscription::findOrFail($request->subs_id);
        $settings = Generalsetting::findOrFail(1);
        $today = Carbon::now()->format('Y-m-d');
        $input = $request->all();
        $user->is_vendor = 2;
        if (!empty($package)) {
            if ($package->subscription_id == $request->subs_id) {
                $newday = strtotime($today);
                $lastday = strtotime($user->date);
                $secs = $lastday - $newday;
                $days = $secs / 86400;
                $total = $days + $subs->days;
                $user->date = date('Y-m-d', strtotime($today . ' + ' . $total . ' days'));
            } else {
                $user->date = date('Y-m-d', strtotime($today . ' + ' . $subs->days . ' days'));
            }
        } else {
            $user->date = date('Y-m-d', strtotime($today . ' + ' . $subs->days . ' days'));
        }
        $user->mail_sent = 1;
        $user->update($input);
        $sub = new UserSubscription;
        $sub->user_id = $user->id;
        $sub->subscription_id = $subs->id;
        $sub->title = $subs->title;
        $sub->currency_sign = $this->curr->sign;
        $sub->currency_code = $this->curr->name;
        $sub->currency_value = $this->curr->value;
        $sub->price = $subs->price;
        $sub->days = $subs->days;
        $sub->allowed_products = $subs->allowed_products;
        $sub->details = $subs->details;
        $sub->method = 'Paystack';
        $sub->txnid = $request->txnid;

        $sub->status = 1;
        $sub->save();
        if ($settings->is_smtp == 1) {
            $data = [
                'to' => $user->email,
                'type' => "vendor_accept",
                'cname' => $user->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'onumber' => "",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);
        } else {
            $headers = "From: " . $settings->from_name . "<" . $settings->from_email . ">";
            mail($user->email, 'Your Vendor Account Activated', 'Your Vendor Account Activated Successfully. Please Login to your account and build your own shop.', $headers);
        }

        return redirect()->route('user-dashboard')->with('success', __('Vendor Account Activated Successfully'));
    }
}
