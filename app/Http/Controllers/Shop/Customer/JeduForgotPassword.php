<?php

namespace App\Http\Controllers\Shop\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CustomerForgotPasswordRequest;
use App\Models\Shop\JeduCustomer;
use App\Services\OtpService;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class JeduForgotPassword extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Contains route related configuration.
     *
     * @var string
     */
    protected $token;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerForgotPasswordRequest $request)
    {
        $request->validated();
        $customer = JeduCustomer::query()
            ->where('phone', request()->input()['phone'])->firstOrFail();
        return redirect()->route(
            'customer.forgot-password.verify.show',
            ['token' => $customer->token]);

    }

    /**
     * Opens up the user's forgot password verification form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function showVerify($token)
    {
        $customer = JeduCustomer::query()
            ->where('token', $token)
            ->firstOrFail();
        if (!$customer->is_verified) {
            session()->flash('حساب کاربری شما فعال نشده است.');
            return redirect()->back();
        }

        $otp = OtpService::getOTP($customer, 'reset');
        return view($this->_config['view'])
            ->with('token', $token)
            ->with('phone', $customer->phone)
            ->with('otp_expire', $otp->remaining());

        //$token = $this->broker()->getToken(request()->only(['phone']));

    }

    /**
     * Opens up the user's forgot password verification form.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function verify(Request $request)
    {
        $customer = JeduCustomer::query()
            ->where('phone', $request->phone)
            ->firstOrFail();
        if (!$customer) {
            session()->flash('warning', trans('responses.customer_not_found'));
            return redirect()->route('customer.session.index');
        }
        if (OtpService::isExpired($customer)) {
            session()->flash('warning', trans('shop::app.customer.signup-form.verify-failed'));
            return redirect()->route('customer.session.index');
        }
        if ($customer->otp->token !== $request->ver_code) {
            session()->flash('warning',
                trans('shop::app.customer.signup-form.verify-failed'));
            return redirect()->route($this->_config['redirect_on_fail'],
                ['token' => $request->token]);
        }
        try {
            $response = $this->broker()
                ->sendResetLink($request->only(['phone']),
                    function ($user, $token){
                        $this->token =$token;
                        \Log::info("token in callbakc:".$token);
                    });

            if ($response === Password::RESET_THROTTLED) {
                session()->flash('success', trans('app.customer.forget_password.too_many_request'));
                return redirect()->back();
            }
        } catch (\Swift_RfcComplianceException $e) {
            session()->flash('success', trans('customer::app.forget_password.reset_link_sent'));
            return redirect()->back();
        } catch (\Exception $e) {
            report($e);
            session()->flash('error', trans($e->getMessage()));
            return redirect()->back();
        }
        if ($this->token){
            return redirect()->route(
                'customer.reset-password.create',
                ['token' => $this->token]);
        }
        return redirect()->back();
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('customers');
    }
}
