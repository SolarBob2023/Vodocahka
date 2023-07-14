<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $table = 'periods';
    protected $guarded = false;
    public $timestamps = false;

    public function record()
    {
        return $this->hasOne(Record::class,'period_id','id');
    }
    public function rate()
    {
        return $this->hasOne(Rate::class,'period_id','id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class,'period_id','id');
    }

    public function residents()
    {
        return $this->belongsToMany(Resident::class,'bills','period_id','resident_id');
    }
}
