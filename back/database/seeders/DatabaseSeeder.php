<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Period;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //Заполение таблицы периодов c прошлого года
        $currentDt = Carbon::now();
        $startDt = Carbon::create(2022);
        $startDt->subMonth();
        $diffMonths = $currentDt->diffInMonths($startDt) - 1;
        for ($i = 0; $i < $diffMonths; $i++){
            $startDt->addMonths(1)->toDateTimeString();
//            dump("Start: " .$startDt);
            $tempDt = $startDt->copy();
            $tempDt->addMonths(1)->subSecond();
//            dump("End: " .$tempDt->toDateTimeString());
//            dump("________________");
            $data = [
                'begin_date' => $startDt->toDateTimeString(),
                'end_date' => $tempDt->toDateTimeString(),
            ];
            $period = Period::create($data);
            if ( $i != $diffMonths - 1){
                $period->record()->create([
                    'amount_volume' => 100 + rand(0,1000)*0.1,
                ]);
            }

            $period->rate()->create([
                'amount_price' => 140 + rand(0,20)-10,
            ]);
        }
        Resident::factory()->count(20)->create();
//        $bills = DB::select('
//                select periods.id as period_id, residents.id as resident_id,
//                amount_volume * amount_price/_ploshad * residents.area as amount_rub
//                    from public.pump_meter_records
//                join public.periods on periods.id = pump_meter_records.period_id
//                join public.residents on residents.start_date <= periods.end_date
//                join public.rates on periods.id = rates.period_id
//                join (SELECT periods.id as _period,  round(sum(area::numeric),2) as _ploshad
//                        FROM public.residents
//                            join public.periods on residents.start_date <= periods.end_date
//                        group by periods.id
//                        order by periods.id
//                     ) as foo on _period = periods.id
//            ');

        $periodPrices = DB::table('periods')
            ->select('periods.id', DB::raw('amount_volume * amount_price as price'))
            ->join('pump_meter_records', 'periods.id', '=', 'pump_meter_records.period_id')
            ->join('rates','periods.id', '=', 'rates.period_id')
            ->orderBy('periods.id')
            ->pluck('price');
        $periodAreas = DB::table('residents')
            ->join('periods', 'residents.start_date','<=','periods.end_date')
            ->select('periods.id', DB::raw('sum(area) as area'))
            ->groupBy('periods.id')
            ->orderBy('periods.id')
            ->pluck('area');
        $periods = Period::all();
        $periods->pop();
        foreach ($periods as $period){
            $periodId = $period->id;
            $residents = Resident::where('start_date','<=',$period->end_date)->get();
            //TODO исправить, чтобы при повторном вызове seeder бд не заполнялась
            foreach ($residents as $resident){
                $period->bills()->updateOrCreate([
                    'resident_id' => $resident->id,
                    'amount_rub' => $periodPrices[$periodId-1]/$periodAreas[$periodId-1]*$resident->area,
                ]);
            }
        }

        //Добавление текущего периода и тарифа к к нему
        $startDt->addMonths(1)->toDateTimeString();
        $tempDt = $startDt->copy();
        $tempDt->addMonths(1)->subSecond();
        $data = [
            'begin_date' => $startDt->toDateTimeString(),
            'end_date' => $tempDt->toDateTimeString(),
        ];
        $period = Period::create($data);
        $period->rate()->create([
            'amount_price' => 140 + rand(0,20)-10,
        ]);
    }
}
