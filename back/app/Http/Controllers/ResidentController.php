<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResidentResource;
use App\Models\Bill;
use App\Models\Period;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'fio' => 'required|string',
            'area' => 'required|numeric',
            'start_date' => 'required|date',
        ]);

        //Проверка что указанного дачника нет в базе данных
        $resident = Resident::where('fio', '=', $data['fio'])
            ->where('area', $data['area'])->first();
        if ($resident) {
            return response()
                ->json([
                    'errors' => ['message' => ['Дачник с таким участком уже подключен']]
                ], 422);

        }
        //вычисление периода
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $startDt = Carbon::create($data['start_date']);
        $reqDt = Carbon::create($startDt->year, $startDt->month);
        $periodId = Carbon::create(2022)->diffInMonths($reqDt) + 1;
        $diffMonths = $currentDt->diffInMonths($reqDt, false);
        if ($diffMonths >= 0) {

            //Проверка на наличие предудщих периодов в бд
            DB::beginTransaction();
            try {
                $lastPeriod = Period::max('id');
                if ($lastPeriod - $periodId < 0) {
                    $lastDtBegin = Carbon::create(2022)->addMonths($lastPeriod);
                    for ($i = 0; $i < abs($lastPeriod - $periodId); $i++) {
                        Period::updateOrCreate([
                            'begin_date' => $lastDtBegin,
                            'end_date' => $lastDtBegin->copy()->addMonth()->subDay()
                        ]);
                        $lastDtBegin->addMonth();
                    }
                }
                $resident = Resident::create($data);
            } catch (\Exception $exception) {
                DB::rollback();
                throw $exception;
            }
            DB::commit();

            return ResidentResource::make($resident);
        } elseif ($diffMonths == -1) {
            $maxBillPeriod = Bill::max('period_id');
            if ($maxBillPeriod >= $periodId) {
                return response()
                    ->json([
                        'errors' => ['start_date' => ['Нельзя добавить дачника в период, по которому уже выставлены счета']]
                    ], 422);
            }
            DB::beginTransaction();
            try {
                $resident = Resident::create($data);
            } catch (\Exception $exception) {
                DB::rollback();
                throw $exception;
            }
            DB::commit();

            return ResidentResource::make($resident);
        } else {
            return response()
                ->json([
                    'errors' => ['start_date' => ['нельзя добавить дачника, у которого дата подключения раньше, чем последний расчётный период']]
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

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $residents = Resident::orderBy('id', 'DESC')->paginate(10);
        return ResidentResource::collection($residents);
    }
}
