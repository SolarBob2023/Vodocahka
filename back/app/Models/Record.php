<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;
    protected $table = 'pump_meter_records';
    protected $guarded = false;
    public $timestamps = false;

}