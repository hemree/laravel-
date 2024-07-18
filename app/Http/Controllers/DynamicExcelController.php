<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\DynamicExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DynamicExcelData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DynamicExcelController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Dosya yükleme işlemi başladı.');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Log::info('Dosya bilgisi: ' . json_encode($file->getClientOriginalName()));

            if ($file->isValid()) {
                Log::info('Yüklenen dosya geçerli: ' . $file->getPathname());

                // Dosyayı 'uploads' dizinine kaydet
                $path = $file->store('uploads');
                Log::info('Dosya şu dizine kaydedildi: ' . $path);

                try {
                    // Dosya yolunu gerçek yolla değiştirin
                    $data = $this->processExcelFile(storage_path('app/' . $path));
                    Log::info('Excel dosyası başarıyla işlendi.');

                    $this->compareFiles($data, public_path('reference_file.xlsx')); // Referans dosya yolunu belirtin
                    Log::info('Dosya karşılaştırması başarıyla yapıldı.');

                    foreach ($data as $row) {
                        DynamicExcelData::create([
                            'data' => json_encode($row)
                        ]);
                    }

                    Log::info('Veriler başarıyla kaydedildi.');
                    return redirect()->back()->with('success', 'Veriler başarıyla yüklendi.');
                } catch (\Exception $e) {
                    Log::error('Dosya yüklenirken hata oluştu: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Dosya yüklenirken bir hata oluştu: ' . $e->getMessage());
                }
            } else {
                Log::warning('Geçersiz dosya yüklemesi: ' . $file->getPathname());
                return redirect()->back()->with('error', 'Geçersiz dosya yüklemesi.');
            }
        } else {
            Log::warning('Hiçbir dosya yüklenmedi.');
            return redirect()->back()->with('error', 'Hiçbir dosya yüklenmedi.');
        }
    }

    public function index()
    {
        return view('upload');
    }

    private function processExcelFile($filePath)
    {
        $data = Excel::toArray(new DynamicExcelImport, $filePath);

        // Verileri işlemek ve standart formata getirmek
        $processedData = [];
        $columnsMap = $this->getColumnsMap();

        foreach ($data[0] as $row) {
            $processedRow = [];
            foreach ($columnsMap as $standardColumn => $possibleColumns) {
                foreach ($possibleColumns as $possibleColumn) {
                    if (isset($row[$possibleColumn])) {
                        $processedRow[$standardColumn] = $row[$possibleColumn];
                        break;
                    }
                }
            }
            $processedData[] = $processedRow;
        }

        return $processedData;
    }

    private function getColumnsMap()
    {
        // Farklı dosya formatlarındaki sütun isimlerini standart isimlerle eşleştirin
        return [
            'sku' => ['SKU', 'sku'],
            'ean' => ['EAN', 'ean'],
            'name' => ['Name', 'name'],
            'url' => ['URL', 'url'],
            'image_1_link' => ['Image 1 Link', 'image_1_link'],
            'image_2_link' => ['Image 2 Link', 'image_2_link'],
            'weight_incl_package' => ['Weight (incl. Package) in kg', 'weight_incl_package'],
            'weight_excl_package' => ['Weight (excl. Package) in kg', 'weight_excl_package'],
            'size_package' => ['Size Package (LxWxH) in cm', 'size_package'],
            'category' => ['Category', 'category'],
            'color' => ['Color', 'color'],
            'material' => ['Material', 'material'],
            'brand' => ['Brand', 'brand'],
            'description' => ['Description', 'description'],
            'bullet_points' => ['Bullet Points', 'bullet_points'],
            'qty' => ['Qty', 'qty'],
            'size' => ['Size', 'size'],
            'package' => ['Package', 'package'],
            'wholesale_price' => ['Wholesale Price', 'wholesale_price'],
            'shipping_cost' => ['Shipping Cost', 'shipping_cost'],
            'shipping_cost_germany' => ['Shipping Cost Germany', 'shipping_cost_germany'],
            'specification' => ['Specification', 'specification'],
            'package_list' => ['Package list', 'package_list'],
        ];
    }

    private function compareFiles($uploadedData, $referenceFilePath)
    {
        $referenceData = Excel::toArray(new DynamicExcelImport, $referenceFilePath)[0];

        $referenceColumns = array_keys($referenceData[0]);
        $uploadedColumns = array_keys($uploadedData[0]);

        $missingColumns = array_diff($referenceColumns, $uploadedColumns);
        $extraColumns = array_diff($uploadedColumns, $referenceColumns);

        Log::info('Eksik Sütunlar: ' . implode(', ', $missingColumns));
        Log::info('Fazla Sütunlar: ' . implode(', ', $extraColumns));

        // Eksik veya fazla sütunlar varsa hata mesajı döndürün
        if (!empty($missingColumns) || !empty($extraColumns)) {
            throw new \Exception('Dosya sütunlarında farklılıklar var.');
        }
    }
}
