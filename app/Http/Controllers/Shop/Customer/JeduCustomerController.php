<?php

namespace App\Http\Controllers\Shop\Customer;

use App\Http\Requests\Shop\CustomerProfileRequest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Webkul\Core\Repositories\SubscribersListRepository;
use Webkul\Customer\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Shop\Mail\SubscriptionEmail;

class JeduCustomerController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Customer repository instance.
     *
     * @var \Webkul\Customer\Repositories\CustomerRepository
     */
    protected $customerRepository;

    /**
     * Product review repository instance.
     *
     * @var \Webkul\Customer\Repositories\ProductReviewRepository
     */
    protected $productReviewRepository;

    /**
     * Subscribers list repository instance.
     *
     * @var \Webkul\Core\Repositories\SubscribersListRepository
     */
    protected $subscriptionRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReviewRepository
     * @param  \Webkul\Core\Repositories\SubscribersListRepository  $subscriptionRepository
     * @return void
     */
    public function __construct(
        CustomerRepository $customerRepository,
        ProductReviewRepository $productReviewRepository,
        SubscribersListRepository $subscriptionRepository
    ) {
        $this->middleware('customer');

        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;

        $this->productReviewRepository = $productReviewRepository;

        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * For loading the edit form page.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $customer = $this->customerRepository->find(auth()->guard('customer')->user()->id);

        return view($this->_config['view'], compact('customer'));
    }

    /**
     * Edit function for editing customer profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerProfileRequest $customerProfileRequest)
    {
        $isPasswordChanged = false;

        $data = $customerProfileRequest->validated();

        if (isset($data['date_of_birth']) && $data['date_of_birth'] == '') {
            unset($data['date_of_birth']);
        }

        $data['subscribed_to_news_letter'] = isset($data['subscribed_to_news_letter']) ? 1 : 0;

        if (($data['oldpassword'] != "" || $data['oldpassword'] != null)) {
            if (Hash::check($data['oldpassword'],
                auth()->guard('customer')->user()->password)
            ) {
                $isPasswordChanged = true;

                $data['password'] = bcrypt($data['password']);
            } else {
                session()->flash('warning',
                    trans('shop::app.customer.account.profile.unmatch'));

                return redirect()->back();
            }
        } else {
            if ($data['password']) {
                if (auth()->guard('customer')->user()->password) {
                    session()->flash('warning',
                        trans('shop::app.customer.account.profile.unmatch'));
                    return redirect()->back();
                }
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }

        }

        Event::dispatch('customer.update.before');
        if (!isset($data['email']) || $data['email'] === ""){
            $data['email'] = $data['national_code'] ."@jedu.ir";
        }
        if ($customer = $this->customerRepository->update($data, auth()->guard('customer')->user()->id)) {
            if ($isPasswordChanged) {
                Event::dispatch('user.admin.update-password', $customer);
            }

            Event::dispatch('customer.update.after', $customer);

            if ($data['subscribed_to_news_letter']) {
                $subscription = $this->subscriptionRepository->findOneWhere(['email' => $data['email']]);

                if ($subscription) {
                    $this->subscriptionRepository->update([
                        'customer_id'   => $customer->id,
                        'is_subscribed' => 1,
                    ], $subscription->id);
                } else {
                    $this->subscriptionRepository->create([
                        'email'         => $data['email'],
                        'customer_id'   => $customer->id,
                        'channel_id'    => core()->getCurrentChannel()->id,
                        'is_subscribed' => 1,
                        'token'         => $token = uniqid(),
                    ]);

                    try {
                        Mail::queue(new SubscriptionEmail([
                            'email' => $data['email'],
                            'token' => $token,
                        ]));
                    } catch (\Exception $e) {
                    }
                }
            } else {
                $subscription = $this->subscriptionRepository->findOneWhere(['email' => $data['email']]);

                if ($subscription) {
                    $this->subscriptionRepository->update([
                        'customer_id'   => $customer->id,
                        'is_subscribed' => 0,
                    ], $subscription->id);
                }
            }

            $this->customerRepository->uploadImages($data, $customer);

            Session()->flash('success',
                trans('shop::app.customer.account.profile.edit-success'));

            return redirect()->route($this->_config['redirect']);
        }
        Session()->flash('success',
            trans('shop::app.customer.account.profile.edit-fail'));

        return redirect()->back($this->_config['redirect']);

    }
}
