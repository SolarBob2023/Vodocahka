<?php

namespace App\Http\Service;

use Carbon\Carbon;

class Service
{
    public static function periodToDiffMonts(int $period) : int{
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $reqDt = Carbon::create(2022);
        $reqDt->addMonths($period - 1);
        return $currentDt->diffInMonths($reqDt, false);;
    }

    public static function periodToDt(int $period):Carbon
    {
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $reqDt = Carbon::create(2022);
        return $reqDt->addMonths($period - 1);
    }

    public static function dtToPeriod(string $date): int
    {
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $startDt = Carbon::create($date);
        $reqDt = Carbon::create($startDt->year, $startDt->month);
        return Carbon::create(2022)->diffInMonths($reqDt) + 1;
    }
}
