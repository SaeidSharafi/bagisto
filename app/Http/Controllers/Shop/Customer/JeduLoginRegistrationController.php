<?php

namespace App\Http\Controllers\Shop\Customer;

use App\Jobs\SendSMS;
use App\Requests\Shop\JeduCustomerRegistrationRequest;
use App\Services\OTP;
use App\Services\PrepareOtpSms;

use App\Services\SmsBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Webkul\Customer\Mail\RegistrationEmail;
use Webkul\Customer\Mail\VerificationEmail;

class JeduLoginRegistrationController
    extends \Webkul\Customer\Http\Controllers\RegistrationController
{

    /**
     * Opens up the user's sign up form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if (auth()->guard('customer')->check()) {
            return redirect()->route('customer.profile.index');
        }
        return view($this->_config['view']);
    }

    public function smscomplete()
    {
        //ddd(request()->input());
        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @param  JeduCustomerRegistrationRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(JeduCustomerRegistrationRequest $request
    ): RedirectResponse {

        //dd((strtotime('+300 seconds')));
        //$request->validated();
        //$params = ['code' => "123456"];
        //$options = [
        //    'numbers'    => ["09359933642"],
        //    'pattern'    => 'mdoe1j1587',
        //    'parameters' => $params,
        //];
        //$otp =  new SendOTP($options);
        //$bl = $otp->build();
        //$bl->sendSMS();

        $customer = $this->customerRepository->findOneByField('phone',
            request()->input()['phone']);

        if (!$customer) {
            $is_verified
                = (core()->getConfigData('customer.settings.email.verification')
                || core()->getConfigData('customer.settings.sms.verification'))
                ? 0
                : 1;
            $data = array_merge(request()->input(), [
                'api_token'                 => Str::random(80),
                'is_verified'               => $is_verified,
                'customer_group_id'         => $this->customerGroupRepository->findOneWhere(['code' => 'general'])->id,
                'token'                     => md5(uniqid(rand(), true))
                    .md5(request()->input()['phone']),
                'subscribed_to_news_letter' => isset(request()->input()['is_subscribed'])
                    ? 1 : 0,
            ]);
            Event::dispatch('customer.registration.before');
            $customer = $this->customerRepository->create($data);
        }
        if (!$customer->is_verified) {
            if (core()->getConfigData('customer.settings.sms.verification')) {
                return redirect()->route($this->_config['redirect_verify'],
                    ['token' => $customer->token]);
            }

        } else {
            $type = "login_by_otp";
            if ($customer->password) {
                $type = "login_by_password";
            }
            return redirect()->route(
                'customer.session.index',
                ['token' => $customer->token, 'type' => $type]);
        }

        Event::dispatch('customer.registration.after', $customer);

        if (!$customer) {
            session()->flash('error',
                trans('shop::app.customer.signup-form.failed'));

            return redirect()->back();
        }

        /*if (isset($data['is_subscribed'])) {
            $subscription
                = $this->subscriptionRepository->findOneWhere(['email' => $data['email']]);

            if ($subscription) {
                $this->subscriptionRepository->update([
                    'customer_id' => $customer->id,
                ], $subscription->id);
            } else {
                $this->subscriptionRepository->create([
                    'email'         => $data['email'],
                    'customer_id'   => $customer->id,
                    'channel_id'    => core()->getCurrentChannel()->id,
                    'is_subscribed' => 1,
                    'token'         => $token = md5(uniqid(rand(), true)),
                ]);

                try {
                    Mail::queue(new SubscriptionEmail([
                        'email' => $data['email'],
                        'token' => $token,
                    ]));
                } catch (\Exception $e) {
                }
            }
        }*/

        if (core()->getConfigData('customer.settings.email.verification')) {
            try {
                if (core()->getConfigData('emails.general.notifications.emails.general.notifications.verification')) {
                    Mail::queue(new VerificationEmail([
                        'email' => $data['email'], 'token' => $data['token']
                    ]));
                }

                session()->flash('success',
                    trans('shop::app.customer.signup-form.success-verify'));
            } catch (\Exception $e) {
                report($e);

                session()->flash('info',
                    trans('shop::app.customer.signup-form.success-verify-email-unsent'));
            }
        } else {
            try {
                if (core()->getConfigData('emails.general.notifications.emails.general.notifications.registration')) {
                    Mail::queue(new RegistrationEmail(request()->all(),
                        'customer'));
                }

                if (core()->getConfigData('emails.general.notifications.emails.general.notifications.customer-registration-confirmation-mail-to-admin')) {
                    Mail::queue(new RegistrationEmail(request()->all(),
                        'admin'));
                }

                session()->flash('success',
                    trans('shop::app.customer.signup-form.success-verify'));
            } catch (\Exception $e) {
                report($e);

                session()->flash('info',
                    trans('shop::app.customer.signup-form.success-verify-email-unsent'));
            }

            session()->flash('success',
                trans('shop::app.customer.signup-form.success'));
        }

        //dd($this->_config['redirect'],$this);
        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Method to verify account.
     *
     * @param  Request  $request
     * @param  string  $token
     *
     * @return RedirectResponse
     */
    public function verifyAccountWithSMS(Request $request)
    {

        $customer = $this->customerRepository->findOneByField('phone',
            $request->phone);
        if (!$customer) {
            session()->flash('warning',
                trans('responses.customer_not_found'));
            return redirect()->route('customer.session.index');
        }
        if (OTP::isExpired($customer)) {
            session()->flash('warning',
                trans('shop::app.customer.signup-form.verify-failed'));
            return redirect()->route('customer.session.index');
        }
        if ($customer->otp !== $request->ver_code) {
            session()->flash('warning',
                trans('shop::app.customer.signup-form.verify-failed'));
            return redirect()->route($this->_config['redirect_on_fail'],
                ['token' => $request->token]);
        }
        $customer->update(['is_verified' => 1]);
        session()->flash('success',
            trans('shop::app.customer.signup-form.verified'));
        auth()->guard('customer')->loginUsingId($customer->id);
        OTP::clearOTP($customer);
        return redirect()->route('customer.session.index');

    }

    /**
     * Opens up the user's sign up form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function showverfiy($token)
    {
        $customer
            = $this->customerRepository->findOneByField('token',
            $token);
        $id = $customer;
        if (!$customer || $customer->is_verified) {
            return redirect()->back();
        }

        if (core()->getConfigData('customer.settings.sms.verification')) {
            if (core()->getConfigData('sms.general.notifications.verification.status')) {
                $customer = OTP::getOTP($customer);
                return view($this->_config['view'])
                    ->with('token', $token)
                    ->with('phone', $customer->phone)
                    ->with('otp_expire', ($customer->otp_expire - time()));
            }
        }
        return redirect()->back();
    }
}