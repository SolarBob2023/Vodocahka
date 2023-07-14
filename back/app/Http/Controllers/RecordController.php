<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecordResource;
use App\Models\Period;
use App\Models\Record;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'period' => 'required|integer',
            'volume' => 'required|integer',
        ]);
        //TODO транзакция
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $reqDt = Carbon::create(2022);
        $reqDt->addMonths($data['period'] - 1);
        $diffMonths = $currentDt->diffInMonths($reqDt, false);
        if ($diffMonths == -1) {
            $period = Period::find($data['period']);
            $record = $period->record()->updateOrCreate(
                ['period_id' => $data['period']],
                ['amount_volume' => $data['volume']]
            );
            $periodPrice = $period->rate->amount_price * $data['volume'];
            $periodArea = Resident::where('start_date', '<=', $period->end_date)->sum('area');
            //Chunk на всякий случай
            Resident::where('start_date', '<=', $period->end_date)->chunk(10,
                function ($residents) use ($period, $periodPrice, $periodArea) {
                    foreach ($residents as $resident) {
                        $period->bills()->updateOrCreate([
                            'resident_id' => $resident->id,
                        ], [
                            'resident_id' => $resident->id,
                            'amount_rub' => $periodPrice / $periodArea * $resident->area,
                        ]);
                    }
                });

            return RecordResource::make($record);
        } else {
            return response()
                ->json([
                    'errors' => ['period' => ['Резрешено заносить показания счётчика водокачки, только за предыдщущий месяц']]
                ], 422);
        }
    }

    public function index()
    {
        $records = Record::orderBy('period_id', 'DESC')->paginate(5);
        return RecordResource::collection($records);
    }
}
