<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillResource;
use App\Http\Resources\ResidentResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Period $period) : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $bills = $period->bills()->with('resident')->orderBy('amount_rub', 'DESC')->paginate(10);
        return BillResource::collection($bills);
    }
}
