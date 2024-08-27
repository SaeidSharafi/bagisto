<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCSVService
{
    public function import(
        ToCollection $importableToCollection,
        UploadedFile|string $file,
        bool $write
    ): null|array {

        $importableToCollection->import($file);
        if ($write && $importableToCollection->failures()->isEmpty()) {
            return null;
        }
        $collection = $importableToCollection->toCollection($file)->first();

        $errors = null;
        $data = [];

        foreach ($importableToCollection->failures() as $failure) {
            $errors[$failure->row()][$failure->attribute()] = $failure->errors();
        }
        foreach ($collection as $index => $row) {
            $data[$index]['row'] = $index + 2;
            $data[$index]['values'] = $row;
            $data[$index]['errors'] = [];
            if ($errors) {
                if (array_key_exists($index + 2, $errors)) {
                    $data[$index]['errors'] = $errors[$index + 2];
                }
            }
        }

        return $data;
    }
}