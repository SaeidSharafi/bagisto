<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CustomersImport;
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
        return view('admin::customers.bulk')->with('data', null);
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'uploaded_file' => 'required|file|mimes:xls,xlsx,csv',
        ]);
        $import = new CustomersImport();

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

            if (isset($row['date_of_birth'])) {
                $row['date_of_birth'] = Carbon::make($row['date_of_birth'])->jdate("Y-m-d");
            }
            $data[$index]['values'] = $row;
            if (array_key_exists($index, $errors)) {
                $data[$index]['errors'] = $errors[$index];
            }
        }
        $data = collect($data);
        //dd($data->filter(function ($item){
        //    return array_key_exists('errors', $item);
        //}));

        MoodleService::createUsers($data->pluck('values')->toArray());

        session()->flash("import successful");
        return view('admin::customers.bulk')
            ->with('failures', collect($errors))
            ->with('data', $data);

    }
}
