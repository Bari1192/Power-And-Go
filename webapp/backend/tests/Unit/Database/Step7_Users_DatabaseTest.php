<?php

namespace Tests\Unit\Database;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Step7_Users_DatabaseTest extends TestCase
{
    use DatabaseTransactions;
    public function test_users_table_exists_in_database()
    {
        $this->assertTrue(
            Schema::hasTable('users'),
            'A `users` tábla nem jött létre az adatbázisban.'
        );
    }
    public function test_users_table_is_not_empty_after_seeding()
    {
        $this->assertGreaterThan(
            0,
            User::count(),
            'A `users` tábla Seedelés után sem tartalmaz adatrekordot.'
        );
    }
    public function test_users_table_has_correct_columns()
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'person_id',
            'sub_id',
            'account_balance',
            'password_2_4',
            'user_name',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ]));
    }
    public function test_users_table_has_correct_columns_and_types()
    {
        $columns = [
            'id' => 'bigint',
            'person_id' => 'bigint',
            'sub_id' => 'bigint',
            'account_balance' => 'int',
            'password_2_4' => 'varchar',
            'user_name' => 'varchar',
            'password' => 'varchar',
            'remember_token' => 'varchar',
        ];

        foreach ($columns as $column => $type) {
            $this->assertTrue(Schema::hasColumn('users', $column), "A {$column} oszlop nem jött létre a `users` táblában.");
            $this->assertEquals($type, Schema::getColumnType('users', $column), "A {$column} mező típusa nem egyezik az elvárt típussal.");
        }
    }
    public function test_users_columns_allows_maximum_and_minimum_migration_lenght()
    {
        $maxPerson = Person::factory()->create();
        $minPerson = Person::factory()->create();
        $maxLength = [
            'person_id' => $minPerson['id'],
            'sub_id' => 1,
            'account_balance' => 100000,
            'password_2_4' => '66',
            'user_name' => fake()->regexify('[A-Za-z0-9]{45}'),
            'password' => str_repeat('6', 8),
        ];
        $minLength = [
            'person_id'=>$maxPerson['id'],
            'sub_id' => 1,
            'account_balance' => 0,
            'password_2_4' => '66',
            'user_name' => fake()->regexify('[A-Za-z0-9]{3}'),
            'password' => str_repeat('6', 8),
        ];
        $response = $this->postJson('/api/users', $maxLength);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'person_id' => $maxLength['person_id'],
            'sub_id' => $maxLength['sub_id'],
            'account_balance' => $maxLength['account_balance'],
            'password_2_4' => $maxLength['password_2_4'],
            'user_name' => $maxLength['user_name'],
            'password' => $maxLength['password'],
        ]);

        $response = $this->postJson('/api/users', $minLength);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'person_id' => $minLength['person_id'],
            'sub_id' => $minLength['sub_id'],
            'account_balance' => $minLength['account_balance'],
            'password_2_4' => $minLength['password_2_4'],
            'user_name' => $minLength['user_name'],
            'password' => $minLength['password'],
        ]);
    }
    public function test_users_columns_cannot_allows_out_of_maximum_and_minimum_migration_lenght_and_format()
    {
        $data = [
            'person_id' => 999999,
            'sub_id' => 9999999,
            'account_balance' => -1,
            'password_2_4' => '123',
            'user_name' => '',
            'password' => str_repeat('a', 7),
        ];

        $response = $this->postJson('/api/users', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'person_id',
            'sub_id',
            'account_balance',
            'password_2_4',
            'user_name',
            'password',
        ]);
    }
}
