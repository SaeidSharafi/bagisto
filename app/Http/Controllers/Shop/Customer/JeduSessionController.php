<?php

namespace App\Http\Controllers\Shop\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\SendSMS;
use App\Requests\Shop\JeduCustomerLoginRequest;
use App\Services\OTP;
use App\Services\PrepareOtpSms;
use App\Services\SmsBuilder;
use Illuminate\Support\Facades\Event;
use PharIo\Version\Exception;
use Webkul\Customer\Http\Requests\CustomerLoginRequest;
use Webkul\Customer\Repositories\CustomerRepository;

class JeduSessionController
    extends Controller
{

    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customer
     *
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository
    ) {
        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;

    }

    /**
     * Display the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        if (auth()->guard('customer')->check()) {
            return redirect()->route('customer.profile.index');
        }
        if (!request()->exists('token')) {
            return redirect()->route('customer.auth.create');
        }

        $customer = $this->customerRepository->findOneByField('token',
            request()->input()['token']);

        abort_if(!$customer, 404);

        if (!$customer->is_verified
            && core()->getConfigData('customer.settings.sms.verification')
        ) {

            return redirect()->route($this->_config['redirect_verify'],
                ['token' => $customer->token]);

        }

        if (request()->input()['type'] === 'login_by_password') {
            return view($this->_config['view'])
                ->with('type', 'login_by_password');
        }

        $customer = OTP::getOTP($customer, 180);

        return view($this->_config['view'])
            ->with('type', 'login_by_otp')
            ->with('phone', $customer->phone)
            ->with('otp_expire', OTP::remaining($customer));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  JeduCustomerLoginRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(JeduCustomerLoginRequest $request)
    {

        $request->validated();
        $credentials = $request->only('token', 'otp', 'password');
        $credentials['token'] = trim($credentials['token']);

        if (!auth()->guard('customer')->attempt($credentials)) {
            session()->flash('error',
                trans('shop::app.customer.login-form.invalid-creds'));
            return redirect()->back();
        }

        if (auth()->guard('customer')->user()->status == 0) {
            auth()->guard('customer')->logout();

            session()->flash('warning',
                trans('shop::app.customer.login-form.not-activated'));
            return redirect()->back();
        }

        if (auth()->guard('customer')->user()->is_verified == 0) {
            session()->flash('info',
                trans('shop::app.customer.login-form.verify-first'));
            //return redirect()->route($this->_config['redirect_verify'],
            //    ['token' => $request->get('token')]);
            //Cookie::queue(Cookie::make('enable-resend', 'true', 1));
            //
            //Cookie::queue(Cookie::make('email-for-resend',
            //    $request->get('email'), 1));

            auth()->guard('customer')->logout();
            return redirect()->back();
        }

        /**
         * Event passed to prepare cart after login.
         */
        Event::dispatch('customer.after.login', $request->get('token'));

        $customer = $this->customerRepository->findOneByField('token',
            $request->get('token'));
        OTP::clearOTP($customer);

        return redirect()->intended(route($this->_config['redirect']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        auth()->guard('customer')->logout();

        Event::dispatch('customer.after.logout', $id);

        return redirect()->route($this->_config['redirect']);
    }
}