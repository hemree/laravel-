<?php



namespace App\Imports;

use App\Models\ExcelData;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelDataImport implements ToModel
{
    public function model(array $row)
    {
        return new ExcelData([
            'column1' => $row[0],
            'column2' => $row[1],
            'column3' => $row[2],
            'column4' => $row[3],
        ]);
    }
}

