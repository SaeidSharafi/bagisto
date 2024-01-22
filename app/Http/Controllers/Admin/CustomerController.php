<?php

namespace App\Http\Controllers\Admin;

use App\Imports\CustomersImport;
use App\Models\Shop\JeduCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Webkul\Customer\Repositories\CustomerRepository;

class CustomerController extends \App\Http\Controllers\Controller
{

    public function __construct(
        protected CustomerRepository $customerRepository,
    ) {

    }

    public function index(Request $request)
    {
        return view('admin::customers.bulk')->with('data', null);
    }

    public function impersonate(JeduCustomer $customer)
    {
        auth()->guard('customer')->loginUsingId($customer->id);
        return redirect()->route('customer.my-course.index');
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'uploaded_file' => 'required|file|mimes:xls,xlsx,csv',
        ]);
        $import = new CustomersImport();

        $file = $request->file('uploaded_file')->store('temp');
        $import->import($file);

        $collection = $import->toCollection($file)->first()->filter(function ($value) {
            return !empty($value['national_code']);
        });

        $errors = null;
        $data = null;
        foreach ($import->failures() as $failure) {
            $errors[$failure->row()][$failure->attribute()] = $failure->errors();
        }
        foreach ($collection as $row) {
            $index = $row['row'];

            if (isset($row['date_of_birth'])) {
                $row['date_of_birth'] = Carbon::make($row['date_of_birth'])->jdate("Y-m-d");
            }
            $data[$index]['values'] = $row;
            $data[$index]['errors'] = [];
            if ($errors) {
                if (array_key_exists($index, $errors)) {
                    $data[$index]['errors'] = $errors[$index];
                }
            }
        }
        $keys = $collection->first()->keys()->toArray();
        $error_keys = array_keys(collect($errors)->first());

        session()->flash('success', "import successful");
        return view('admin::customers.bulk')
            ->with('failures', collect($errors))
            ->with('data', collect($data))
            ->with('extra_errors', array_diff($error_keys, $keys));

    }
}