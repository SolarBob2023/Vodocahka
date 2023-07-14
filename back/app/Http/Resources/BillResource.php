<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class BillResource extends JsonResource
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
            'year' => $reqDt->year,
            'month' => $reqDt->monthName,
            'resident_id' => $this->whenLoaded('resident',new MissingValue(),$this->resident_id),
            'bill' => $this->amount_rub,
            'resident' => ResidentResource::make($this->whenLoaded('resident'))
        ];
    }
}
