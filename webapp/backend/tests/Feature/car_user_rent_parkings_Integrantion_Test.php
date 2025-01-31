<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class car_user_rent_parkings_Integrantion_Test extends TestCase
{
    # Step 1 - Check table
    public function test_can_get_the_correct_table_in_database()
    {

        $this->assertTrue(
            Schema::hasTable('car_user_rent_parkings'),
            'A bérlésekhez generálandó töltési tábla nem jött létre.'
        );
    }

    # Step 2 - Check columns
    public function test_can_get_the_correct_table_columns_name()
    {
        $this->assertTrue(
            Schema::hasColumns(
                'car_user_rent_parkings',
                [
                    'id',
                    'rent_id',
                    'parking_start',
                    'parking_end',
                    'parking_minutes',
                ]
            ),
            "Az elvárt mezőnevek nem jöttek létre a parkolási kapcsolótáblában."
        );
    }

    # Step 3 - Check columns keys
    public function test_can_get_correct_columns_key_types()
    {
        $assertColumnTypes = [
            'id' => 'bigint',
            'rent_id' => 'bigint',
            'parking_start' => 'datetime',
            'parking_end' => 'datetime',
            'parking_minutes' => 'int',
        ];
        foreach ($assertColumnTypes as $column => $expectedType) {
            $this->assertEquals(
                $expectedType,
                Schema::getColumnType('car_user_rent_parkings', $column),
                "Nem a megfelelő típusbeállításokat kaptuk a(z) {$column} oszlopnál."
            );
        }
    }
    public function test_can_setup_a_car_with_person_and_user_for_rent()
    {
        # Step 1 - Autó létrehozása
        $car = Car::factory()->create();
        $this->assertEquals([
            "id" => $car->id,
            "fleet_id" => $car->fleet_id,
            "category_id" => $car->category_id,
            "plate" => $car->plate,
            "manufactured" => $car->manufactured,
            "odometer" => $car->odometer,
            "equipment_class" => $car->equipment_class,
            "power_percent" =>  $car->power_percent,
            "power_kw" => $car->power_kw,
            "estimated_range" => $car->estimated_range,
            "status" => $car->status,
        ], $car->only([
            "id",
            "fleet_id",
            "category_id",
            "plate",
            "manufactured",
            "odometer",
            "equipment_class",
            "power_percent",
            "power_kw",
            "estimated_range",
            "status"
        ]));
        # Step 2 - Személy létrehozása
        $person = Person::factory()->create();
        $this->assertEquals([
            'person_password' => $person->person_password,
            'id_card' => $person->id_card,
            'firstname' => $person->firstname,
            'lastname' => $person->lastname,
            'driving_license' => $person->driving_license,
            'license_start_date' => $person->license_start_date,
            'license_end_date' => $person->license_end_date,
            'birth_date' => $person->birth_date,
            'phone' => $person->phone,
            'email' => $person->email,
        ], $person->only([
            'person_password',
            'id_card',
            'firstname',
            'lastname',
            'driving_license',
            'license_start_date',
            'license_end_date',
            'birth_date',
            'phone',
            'email',
        ]));
        # Step 3 - Felhasználó létrehozása
        $user = User::create([
            "person_id" => $person->id,
            "user_name" => fake()->userName(),
            "password" => fake()->password(),
            "password_2_4" => $person->person_password[0] . $person->person_password[2],
            "account_balance" => fake()->numberBetween(1000, 100000),
            "sub_id" => 1,
        ]);
        $this->assertEquals([
            'id' => $user->id,
            'person_id' => $user->person_id,
            'user_name' => $user->user_name,
            'password' => $user->password,
            'password_2_4' => $user->password_2_4,
            'account_balance' => $user->account_balance,
            'sub_id' => $user->sub_id,
        ], $user->only([
            'id',
            'person_id',
            'user_name',
            'password',
            'password_2_4',
            'account_balance',
            'sub_id',
        ]));
    }
}
