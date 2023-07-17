<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reqDt = Carbon::create(2022);
        $reqDt->addMonths($this->period_id - 1);
        return [
            'id' => $this->id,
            'year' => $reqDt->year,
            'month' => $reqDt->monthName,
            'price' => $this->amount_price,
        ];
    }
}
