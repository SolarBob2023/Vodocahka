<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Period;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

         \App\Models\User::factory()->create([
             'name' => 'Admin',
             'surname' =>  fake()->lastName(),
             'patronymic' =>  fake()->lastName(),
             'email' => 'admin@mail.ru',
             'password' => Hash::make('12345678'),
             'role' => 2
         ]);
        \App\Models\User::factory()->create([
            'name' => 'User',
            'surname' =>  fake()->lastName(),
            'patronymic' =>  fake()->lastName(),
            'email' => 'user@mail.ru',
            'role' => 1,
            'password' => Hash::make('12345678'),
        ]);

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
        $periodPrices = DB::table('periods')
            ->select('periods.id', DB::raw('amount_volume * amount_price as price'))
            ->join('pump_meter_records', 'periods.id', '=', 'pump_meter_records.period_id')
            ->join('rates','periods.id', '=', 'rates.period_id')
            ->orderBy('periods.id')
            ->pluck('price');
        $periodAreas = DB::table('residents')
            ->rightJoin('periods', 'residents.start_date','<=','periods.end_date')
            ->select('periods.id', DB::raw('sum(area) as area'))
            ->groupBy('periods.id')
            ->orderBy('periods.id')
            ->pluck('area');
        $periods = Period::all();
        $periods->pop();
        foreach ($periods as $period){
            $periodId = $period->id;
            $residents = Resident::where('start_date','<=',$period->end_date)->get();
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
