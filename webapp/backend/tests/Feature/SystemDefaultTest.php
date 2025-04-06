<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SystemDefaultTest extends TestCase
{
    public function test_RegisterExampleDatatTest(): void
    {
        $response = $this->post('/api/register', [
            "person_password" => "12345678",
            "id_card" => "XXX23456CD",
            "driving_license" => "DXR12345",
            "license_start_date" => "2024-01-01",
            "license_end_date" => "2034-01-01",
            "firstname" => "asd",
            "lastname" => "asd",
            "birth_date" => "1990-01-01",
            "phone" => "+36999234999",
            "email" => "asd@gmail.com",
            "user_name" => "teszteset",
            "pin" => "1234",
            "role"=>"admin",
            "sub_id" => 1
        ]);
        $response->assertStatus(200);
    }
}
