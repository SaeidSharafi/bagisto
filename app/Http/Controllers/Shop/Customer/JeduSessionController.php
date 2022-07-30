<?php

namespace App\Http\Controllers\Shop\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\JeduCustomerLoginRequest;
use App\Models\Shop\Otp;
use App\Services\OtpService;
use App\Services\PrepareOtpSms;
use App\Services\SmsBuilder;
use Illuminate\Support\Facades\Event;
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
            session()->reflash();
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

        $otp = OtpService::getOTP($customer, Otp::VERFIY, 180);
        abort_if(($otp->type === Otp::RESET), 429);
        return view($this->_config['view'])
            ->with('type', 'login_by_otp')
            ->with('phone', $customer->phone)
            ->with('otp_expire', $otp->remaining());
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

        if (array_key_exists('password', $credentials) && !auth()->guard('customer')->attempt($credentials)) {
            session()->flash('error',
                trans('shop::app.customer.login-form.invalid-creds'));
            return redirect()->back();
        }
        $customer = $this->customerRepository->findOneByField('token', $request->get('token'));

        if (!$customer){
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));
            return redirect()->back();
        }
        if (array_key_exists('otp', $credentials) && $customer->otp->token !== $credentials['otp']) {
            session()->flash('error', trans('shop::app.customer.login-form.invalid-creds'));
            return redirect()->back();
        }
        auth()->guard('customer')->login($customer);
        if (auth()->guard('customer')->user()->status == 0) {
            auth()->guard('customer')->logout();

            session()->flash('warning',
                trans('shop::app.customer.login-form.not-activated'));
            return redirect()->back();
        }

        if (auth()->guard('customer')->user()->is_verified == 0) {
            session()->flash('info',
                trans('shop::app.customer.login-form.verify-first'));
            auth()->guard('customer')->logout();
            return redirect()->back();
        }

        /**
         * Event passed to prepare cart after login.
         */
        Event::dispatch('customer.after.login', $request->get('token'));

        $customer = $this->customerRepository->findOneByField('token',
            $request->get('token'));
        OtpService::clearOTP($customer);
        $intended_url = session()->get('url.cart', route($this->_config['redirect']));

        session()->forget('url.cart');

        return redirect()->to($intended_url);
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
        //auth()->guard('customer')->logout();

        Session::flush();
        Auth::logout();
        Event::dispatch('customer.after.logout', $id);

        return redirect()->route($this->_config['redirect']);
    }
}