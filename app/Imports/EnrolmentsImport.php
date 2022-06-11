<?php

namespace App\Imports;

use App\Models\MoodleEnrolment;
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

class EnrolmentsImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure
{

    use Importable, SkipsFailures, RemembersRowNumber;

    /**
     * @param  array  $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MoodleEnrolment([
            'customer_national_code'        => $row['customer_national_code'],
            'moodle_course_id'         => $row['moodle_course_id'],
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            'moodle_course_id'      => 'required|integer',
            'customer_national_code'   => ['required',new Nationalcode()],
        ];

    }

}
