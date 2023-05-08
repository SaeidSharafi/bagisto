<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Repositories\CustomerRepository;

class ResetCustomerPasswordController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    public function __construct(
        protected CustomerRepository $customerRepository,
    ) {
        $this->_config = request('_config');
    }

    public function __invoke($id)
    {
        $customer = $this->customerRepository->findOrFail($id);
        $customer->password = Hash::make($customer->national_code);
        $customer->save();

        session()->flash('success', 'رمزعبور بازنشانی شد');

        return redirect()->route($this->_config['redirect']);
    }
}
