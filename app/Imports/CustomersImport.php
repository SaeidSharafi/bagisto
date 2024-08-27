<?php

namespace App\Imports;

use \App\Models\Shop\JeduCustomer;
use App\Rules\Nationalcode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsEmptyRows,
    SkipsOnError,
    WithLimit
{

    use Importable;
    use RemembersRowNumber;
    use SkipsErrors;
    use SkipsFailures;

    private bool $write;

    public function __construct(bool $write = false)
    {
        $this->write = $write;
    }

    public function collection(Collection $collection)
    {
        if ($this->write) {
            $customers = $collection->map(fn($item) => $this->createCustomer($item));
            DB::table('customers')->upsert($customers->toArray(), ['national_code', 'phone'], [
                'first_name',
                'last_name',
                'gender',
                'date_of_birth',
                'national_code',
                'email',
                'api_token',
                'token',
                'phone',
                'password',
                'customer_group_id',
                'is_verified',
                'father_name',
                'education_field',
                'status',
                'is_moodle_user',
                'moodle_synch',
            ]);
        }
    }

    private function createCustomer($row)
    {
        return [
            'first_name'        => $row['first_name'],
            'last_name'         => $row['last_name'],
            'gender'            => $row['gender'],
            'date_of_birth'     => $row['date_of_birth'],
            'national_code'     => $row['national_code'],
            'email'             => trim($row['email']),
            'api_token'         => Str::random(80),
            'token'             => md5(uniqid(rand(), true)).md5($row['phone']),
            'phone'             => $row['phone'],
            'password'          => bcrypt($row['password']),
            'customer_group_id' => 2,
            'is_verified'       => 1,
            'father_name'       => $row['father_name'],
            'education_field'   => $row['education_field'],
            'status'            => 1,
            'is_moodle_user'    => $row['is_moodle_user'],
            'moodle_synch'      => 0,
        ];
    }

    public function rules(): array
    {
        return [
            'first_name'      => 'required',
            'last_name'       => 'required',
            'gender'          => 'required|in:Other,Male,Female',
            'date_of_birth'   => 'nullable|date|before:today',
            'email'           => 'unique:customers,email',
            'phone'           => 'required|numeric|unique:customers,phone',
            'national_code'   => [
                'required', 'unique:customers,national_code',
                Rule::when(function ($input) {
                    $data = collect($input->getAttributes())->first();
                    return !isset($data['is_foreign']);
                }, [new Nationalcode])
            ],
            'father_name'     => 'nullable',
            'education_field' => 'nullable',
            'is_moodle_user'  => 'required',
        ];

    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'email.unique'         => 'تکراری می باشد',
            'phone.unique'         => 'تکراری می باشد.',
            'national_code.unique' => 'تکراری می باشد.',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        if (!$data || !array_key_exists('national_code', $data)) {
            Log::error('No National COde Field exist', $data);
            return $data;
        }
        if ($data['phone'] && !Str::startsWith($data['phone'], '0')){
            $data['phone'] =   '0'.$data['phone'];
        }
        $data['email'] = $data['email'] ?? $data['national_code']."@jedu.ir";
        $data['father_name'] = $data['father_name'] ?? null;
        $data['education_field'] = $data['education_field'] ?? null;
        if (isset($data['date_of_birth'])) {

            $arr = explode("-", $data['date_of_birth']);

            $date = jalali_to_gregorian($arr[0], $arr[1], $arr[2]);

            $data['date_of_birth'] = implode("-", $date);
        } else {
            $data['date_of_birth'] = null;
        }

        return $data;
    }

    public function limit(): int
    {
        return 200;
    }
}
