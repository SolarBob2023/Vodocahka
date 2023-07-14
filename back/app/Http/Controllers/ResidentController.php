<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResidentResource;
use App\Models\Bill;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'fio' => 'required|string',
            'area' => 'required|numeric',
            'start_date' => 'required|date',
        ]);
        //вычисление периода
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $startDt = Carbon::create($data['start_date']);
        $reqDt = Carbon::create($startDt->year, $startDt->month);
        $period = Carbon::create(2022)->diffInMonths($reqDt);
        $diffMonths = $currentDt->diffInMonths($reqDt, false);
        if ($diffMonths >=0){
            return "будущий период";
        } elseif ($diffMonths == -1) {
            $maxBillPeriod = Bill::max('period_id');
            if ($maxBillPeriod > $period) {
                return response()
                    ->json([
                        'errors' => ['period' => ['Нельзя добавить дачника в период, по которому уже выставлены счета']]
                    ], 422);
            }
            $resident = Resident::where('fio','=', $data['fio'])
                ->where('area', $data['area'])->first();
            if ($resident){
                return response()
                    ->json([
                        'errors' => ['message' => ['Дачник с таким участком уже подключен']]
                    ], 422);

            } else {
                $resident = Resident::create($data);
                return ResidentResource::make($resident);
            }
        } else {
            return response()
                ->json([
                    'errors' => ['period' => ['нельзя добавить дачника, у которого дата подключения раньше, чем последний расчётный период']]
                ], 422);
        }
    }

    public function update(Request $request, Resident $resident)
    {
        $data = $request->validate([
            'fio' => 'required|string',
        ]);
        $resident->update($data);
        return ResidentResource::make($resident);
    }

    public function index() : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $residents = Resident::orderBy('id', 'DESC')->paginate(10);
        return ResidentResource::collection($residents);
    }
}
