<?php

namespace App\Http\Controllers;

use App\Models\ExcelData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ExcelDataImport;


class ExcelDataController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('file');

        Excel::import(new ExcelDataImport, $file);

        return redirect()->back()->with('success', 'Veriler başarıyla içe aktarıldı.');
    }

    public function index()
    {
        $data = ExcelData::all();
        return view('index', compact('data'));
    }
}
