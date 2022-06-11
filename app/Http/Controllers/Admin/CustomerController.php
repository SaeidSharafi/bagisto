<?php

namespace App\Http\Controllers\Admin;

use App\Imports\EnrolmentsImport;
use App\Services\CustomerBulkUploadService;
use App\Services\MoodleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

            if (isset($row['date_of_birth'])) {
                $row['date_of_birth'] = Carbon::make($row['date_of_birth'])->jdate("Y-m-d");
            }
            $data[$index]['values'] = $row;
            if (array_key_exists($index, $errors)) {
                $data[$index]['errors'] = $errors[$index];
            }
        }

        session()->flash('success',"import successful");
        return view('admin::customers.bulk')
            ->with('failures', collect($errors))
            ->with('data',collect($data));

    }
}