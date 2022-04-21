<?php

namespace App\Http\Controllers\Shop\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Webkul\Customer\Http\Controllers\CustomerController;
use Webkul\Customer\Http\Requests\CustomerProfileRequest;
use Webkul\Shop\Mail\SubscriptionEmail;

class JeduCustomerController extends CustomerController
{
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
