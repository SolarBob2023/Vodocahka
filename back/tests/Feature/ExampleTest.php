<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Service\Service;
use App\Models\Bill;
use App\Models\Period;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */

    public function test_rates_index_as_admin()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/rates');
        $response->assertStatus(200);
    }

    public function test_rates_index_as_user()
    {
        $user = User::find(2);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/rates');
        $response->assertStatus(401);
    }

    public function test_record_index_as_admin()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/records');
        $response->assertStatus(200);
    }

    public function test_record_index_as_user()
    {
        $user = User::find(2);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/records');
        $response->assertStatus(401);
    }

    public function test_bill_index_as_admin()
    {
        $user = User::find(1);
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $currentPeriod = Service::dtToPeriod($currentDt);
        Sanctum::actingAs($user);
        $response = $this->get("/api/admin/bills/$currentPeriod");
        $response->assertStatus(200);
    }

    public function test_bill_index_as_user()
    {
        $user = User::find(2);
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $currentPeriod = Service::dtToPeriod($currentDt);
        Sanctum::actingAs($user);
        $response = $this->get("/api/admin/bills/$currentPeriod");
        $response->assertStatus(401);
    }

    public function test_resident_index_as_admin()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/residents');
        $response->assertStatus(200);
    }

    public function test_resident_index_as_user()
    {
        $user = User::find(2);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/residents');
        $response->assertStatus(401);
    }

    //проверка валидации
    public function test_rates_store_is_not_valid()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->post('/api/admin/rates');
        $json = $response->json();
        $this->assertTrue($json['errors']['period'][0] === 'Поле period обязательно.');
        $this->assertTrue($json['errors']['price'][0] === 'Поле стоимость обязательно.');
        $response->assertStatus(422);
    }

    //Прошедший период
    public function test_rates_store_is_not_valid_period()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $currentPeriod = Service::dtToPeriod($currentDt);
        $data =[
            'price' => 150,
            'period' => $currentPeriod - 3
        ];
        $response = $this->post('/api/admin/rates', $data);
        $json = $response->json();
        $this->assertTrue($json['errors']['period'][0] === 'Резрешено изменять цену на тариф, только для будущих периодов');
        $response->assertStatus(422);
    }
    public function test_rates_store_future_period()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $futureDt = Carbon::create(Carbon::now()->year, Carbon::now()->month + 2);
        $futurePeriod = Service::dtToPeriod($futureDt);
        $data =[
            'price' => 150,
            'period' => $futurePeriod
        ];
        $response = $this->post('/api/admin/rates', $data);
        $json = $response->json();
        //проверка что будущие периода добавились в бд
        $lastPeriod = Period::max('id');
        $this->assertTrue($lastPeriod === $futurePeriod);
        //проверка созданного тарифа на соотвествие
        $this->assertTrue($json['data']['year'] === $futureDt->year);
        $this->assertTrue($json['data']['month'] === $futureDt->monthName);
        $this->assertTrue($json['data']['price'] === $data['price']);
        $response->assertStatus(201);
    }

    public function test_record_store_is_not_valid()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->post('/api/admin/records');
        $json = $response->json();
        $this->assertTrue($json['errors']['period'][0] === 'Поле period обязательно.');
        $this->assertTrue($json['errors']['volume'][0] === 'Поле volume обязательно.');
        $response->assertStatus(422);
    }

    public function test_record_store_is_not_valid_period()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $currentDt = Carbon::create(Carbon::now()->year, Carbon::now()->month);
        $currentPeriod = Service::dtToPeriod($currentDt);
        $data =[
            'volume' => 150,
            'period' => $currentPeriod - 2
        ];
        $response = $this->post('/api/admin/records', $data);
        $json = $response->json();
        $this->assertTrue($json['errors']['period'][0] === 'Резрешено заносить показания счётчика водокачки, только за предыдщущий месяц');
        $response->assertStatus(422);
    }

    public function test_resident_store_is_not_valid()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->post('/api/admin/residents');
        $json = $response->json();
        $this->assertTrue($json['errors']['fio'][0] === 'Поле fio обязательно.');
        $this->assertTrue($json['errors']['area'][0] === 'Поле область обязательно.');
        $this->assertTrue($json['errors']['start_date'][0] === 'Поле start date обязательно.');
        $response->assertStatus(422);
    }

    public function test_resident_store_past_period()
    {
        $user = User::find(1);
        $pastDt = Carbon::create(Carbon::now()->year, Carbon::now()->month - 1,4,12);
        Sanctum::actingAs($user);
        $data = [
            'fio' => fake()->name() . ' ' . fake()->lastName(),
            'area' => 78,
            'start_date' => $pastDt->toDateTimeString(),
        ];
        $response = $this->post('/api/admin/residents', $data);
        $json = $response->json();
        $this->assertTrue($json['data']['fio'] === $data['fio']);
        $this->assertTrue($json['data']['area'] === $data['area']);
        $this->assertTrue($json['data']['start_date'] === $data['start_date']);

        $response->assertStatus(201);
    }

    public function test_resident_store_future_period()
    {
        $user = User::find(1);
        $futureDt = Carbon::create(Carbon::now()->year, Carbon::now()->month + 4,4,12);
        $futurePeriod = Service::dtToPeriod($futureDt);
        Sanctum::actingAs($user);
        $data = [
            'fio' => fake()->name() . ' ' . fake()->lastName(),
            'area' => 78,
            'start_date' => $futureDt->toDateTimeString(),
        ];
        $response = $this->post('/api/admin/residents', $data);
        $json = $response->json();
        //проверка что будущие периода добавились в бд
        $lastPeriod = Period::max('id');
        $this->assertTrue($lastPeriod === $futurePeriod);
        $this->assertTrue($json['data']['fio'] === $data['fio']);
        $this->assertTrue($json['data']['area'] === $data['area']);
        $this->assertTrue($json['data']['start_date'] === $data['start_date']);

        $response->assertStatus(201);
    }

    public function test_record_store_past_period()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $pastDt = Carbon::create(Carbon::now()->year, Carbon::now()->month - 1);
        $pastPeriod = Service::dtToPeriod($pastDt);
        $data =[
            'volume' => 150,
            'period' => $pastPeriod
        ];
        $response = $this->post('/api/admin/records', $data);
        $json = $response->json();
        //проверка что выставлены счета
        $maxBillPeriod = Bill::max('period_id');
        $this->assertTrue($pastPeriod === $maxBillPeriod);
        //проверка созданного тарифа на соотвествие
        $this->assertTrue($json['data']['year'] === $pastDt->year);
        $this->assertTrue($json['data']['month'] === $pastDt->monthName);
        $this->assertTrue($json['data']['volume'] === $data['volume']);
        $response->assertStatus(201);
    }

    public function test_resident_store_past_period_when_have_bills()
    {
        $user = User::find(1);
        $pastDt = Carbon::create(Carbon::now()->year, Carbon::now()->month - 1);
        Sanctum::actingAs($user);
        $data = [
            'fio' => fake()->name() . ' ' . fake()->lastName(),
            'area' => 78,
            'start_date' => $pastDt,
        ];
        $response = $this->post('/api/admin/residents', $data);
        $json = $response->json();
        dump($json);
        $this->assertTrue($json['errors']['start_date'][0] === 'Нельзя добавить дачника в период, по которому уже выставлены счета');
        $response->assertStatus(422);
    }

}
