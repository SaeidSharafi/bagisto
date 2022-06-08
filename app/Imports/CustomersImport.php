<?php

namespace App\Imports;

use \App\Models\Shop\JeduCustomer;
use App\Rules\Nationalcode;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure
{

    use Importable, SkipsFailures, RemembersRowNumber;

    /**
     * @param  array  $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new JeduCustomer([
            'first_name'        => $row['first_name'],
            'last_name'         => $row['last_name'],
            'gender'            => $row['gender'],
            'date_of_birth'     => $row['date_of_birth'],
            'national_code'     => $row['national_code'],
            'email'             => $row['email'],
            'phone'             => $row['phone'],
            'password'          => bcrypt($row['password']),
            'customer_group_id' => 2,
            'is_verified'       => 1,
            'father_name'       => $row['father_name'],
            'education_field'   => $row['education_field'],
            'status'            => 1,
            'is_moodle_user'    => $row['is_moodle_user'],
            'moodle_synch'      => 0,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
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
            'national_code'   => ['required', 'unique:customers,national_code', new Nationalcode],
            'father_name'     => 'nullable',
            'education_field' => 'nullable',
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
}
