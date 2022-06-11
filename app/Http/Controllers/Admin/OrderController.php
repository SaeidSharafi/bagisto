<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\EnrolmentsImport;
use App\Services\MoodleService;
use Illuminate\Http\Request;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OrderController extends Controller
{
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected OrderRepository $orderRepository
    ) {

    }

    public function index(Request $request)
    {
        return view('admin::sales.orders.bulk')->with('data', null);
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'uploaded_file' => 'required|file|mimes:xls,xlsx,csv',
        ]);
        $import = new EnrolmentsImport();

        $file = $request->file('uploaded_file')->store('temp');
        $import->import($file);

        $collection = $import->toCollection($file);

        $errors = null;
        $data = null;

        foreach ($import->failures() as $failure) {
            $errors[$failure->row()][$failure->attribute()] = $failure->errors();
        }
        foreach ($collection->first() as $row) {
            $index = $row['row'];

            $data[$index]['values'] = $row;
            if ($errors && array_key_exists($index, $errors)) {
                $data[$index]['errors'] = $errors[$index];
            }
        }
        $data = collect($data);
        //dd($data);
        session()->flash("import successful");
        return view('admin::sales.orders.bulk')
            ->with('failures', collect($errors))
            ->with('data', $data);

    }
}
