<?php

namespace Database\Factories;

use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Resident::class;
    public function definition(): array
    {
        $currentDt = Carbon::now();
        $startDt = Carbon::create(2022);
        $diffMonths = $currentDt->diffInMonths($startDt);
        $startDt = Carbon::create($currentDt->year-1, 1, rand(1,27),rand(1,20));
        $startDt->subMonth();
        $startDt->addMonths(rand(1,$diffMonths));
        return [
            'fio' => fake()->name() . ' ' . fake()->lastName(),
            'area' => 50 + rand(0,500)*0.1,
            'start_date' => $startDt,
        ];
    }
}
