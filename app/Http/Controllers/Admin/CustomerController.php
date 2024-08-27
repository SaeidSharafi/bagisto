<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CustomerImportRequest;
use App\Imports\CustomersImport;
use App\Models\Shop\JeduCustomer;
use App\Services\ImportCSVService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Repositories\CustomerRepository;

class CustomerController extends \App\Http\Controllers\Controller
{

    public function __construct(
        protected CustomerRepository $customerRepository,
        protected ImportCSVService $importCSVService
    ) {

    }

    public function index(Request $request)
    {
        return view('admin::customers.bulk', [
            'data'      => null,
            'validated' => null,
            'file_name' => null,
        ]);
    }

    public function impersonate(JeduCustomer $customer)
    {
        auth()->guard('customer')->loginUsingId($customer->id);
        return redirect()->route('customer.my-course.index');
    }

    public function uploadCSV(CustomerImportRequest $request)
    {
        $validatedData = $request->validated();
        $confirmed = data_get($validatedData, 'validated', false);

        $import = new CustomersImport($confirmed);
        $file = null;
        if ($uploaded_file = data_get($validatedData, 'uploaded_file')) {
            Storage::deleteDirectory('temp'.DIRECTORY_SEPARATOR.'cutsomercsv'.DIRECTORY_SEPARATOR.auth('admin')->user()->id);
            $file = $uploaded_file->store('temp'.DIRECTORY_SEPARATOR.'cutsomercsv'.DIRECTORY_SEPARATOR
                .auth('admin')->user()->id);
        }

        if (!$file) {
            $file = data_get($validatedData, 'file_path');
        }

        if (!$file) {
            abort(404);
        }

        $data = $this->importCSVService->import(
            $import,
            $file,
            $confirmed,
        );
        if (null === $data) {
            session()->flash('success', "import successful");
            return redirect()->route('admin.customer.index');
        }
        return view('admin::customers.bulk', [
            'file_name' => $file,
            'data'      => collect($data),
            'validated' => ($data && $import->failures()->isEmpty()),
        ]);

    }
}