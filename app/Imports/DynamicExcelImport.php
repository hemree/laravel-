<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DynamicExcelImport implements ToCollection, WithHeadingRow
{
    public $data;
    public $columns;

    public function collection(Collection $rows)
    {
        $this->columns = $rows->first()->keys()->toArray();
        $this->data = $rows->toArray();
    }
}
