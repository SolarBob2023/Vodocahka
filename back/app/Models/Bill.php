<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $guarded = false;
    public $timestamps = false;

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id','id');
    }
}
