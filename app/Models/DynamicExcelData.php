<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicExcelData extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function create(array $array)
    {

    }
}
