<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Bill
 *
 * @property int $id
 * @property int $resident_id
 * @property int $period_id
 * @property float $amount_rub
 * @property-read \App\Models\Resident $resident
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereAmountRub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePeriodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereResidentId($value)
 */
	class Bill extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Period
 *
 * @property int $id
 * @property string $begin_date
 * @property string $end_date
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bill> $bills
 * @property-read int|null $bills_count
 * @property-read \App\Models\Rate|null $rate
 * @property-read \App\Models\Record|null $record
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Resident> $residents
 * @property-read int|null $residents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Period newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Period newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Period query()
 * @method static \Illuminate\Database\Eloquent\Builder|Period whereBeginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Period whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Period whereId($value)
 */
	class Period extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Rate
 *
 * @property int $id
 * @property int $period_id
 * @property float $amount_price
 * @method static \Illuminate\Database\Eloquent\Builder|Rate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereAmountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rate wherePeriodId($value)
 */
	class Rate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Record
 *
 * @property int $id
 * @property int $period_id
 * @property float $amount_volume
 * @method static \Illuminate\Database\Eloquent\Builder|Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record query()
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereAmountVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record wherePeriodId($value)
 */
	class Record extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Resident
 *
 * @property int $id
 * @property string $fio
 * @property float $area
 * @property string $start_date
 * @method static \Database\Factories\ResidentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident query()
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereFio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Resident whereStartDate($value)
 */
	class Resident extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property int $role
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

