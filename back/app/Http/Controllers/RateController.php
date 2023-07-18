<?php

namespace App\Http\Controllers;

use App\Http\Resources\RateResource;
use App\Models\Period;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'period' => 'required|integer',
            'price' => 'required|integer',
        ]);
        $currentDt = Carbon::create(Carbon::now()->year,Carbon::now()->month);
        $reqDt = Carbon::create(2022);
        $reqDt->addMonths($data['period'] - 1);
        $diffMonths = $currentDt->diffInMonths($reqDt, false);
        if ($diffMonths > 0){
            DB::beginTransaction();
            try {
                //Проверка на наличие предудщих периодов в бд
                $lastPeriod = Period::max('id');
                if ($lastPeriod - $data['period'] < 0){
                    $lastDtBegin = Carbon::create(2022)->addMonths($lastPeriod);
                    for ($i = 0; $i < abs($lastPeriod - $data['period']); $i++){
                        Period::updateOrCreate([
                            'begin_date' => $lastDtBegin,
                            'end_date' => $lastDtBegin->copy()->addMonth()->subDay()
                        ]);
                        $lastDtBegin->addMonth();
                    }
                }
                $period = Period::find($data['period']);
                $rate = $period->rate()->updateOrCreate(
                    ['period_id' => $data['period']],
                    ['amount_price' => $data['price']]
                );
            } catch (\Exception $exception){
                DB::rollback();
                throw $exception;
            }
            DB::commit();

            return RateResource::make($rate);
        } else {
            return response()->json(['errors'=> ['period' => ['Резрешено изменять цену на тариф, только для будущих периодов']]], 422);
        }
    }

    public function index()
    {
        $rates = Rate::orderBy('period_id', 'DESC')->paginate(10);
        return RateResource::collection($rates);
    }
}
