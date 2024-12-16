<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_get_all_tickets(): void
    {
        $response = $this->getJson('/api/cars');
        $response->assertStatus(200);
    }
   
    public function test_can_create_ticket()
    {
        $ticketData = [
            "car_id" => 10,
            "status_id" => 6,
            "description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."
        ];

        $response = $this->postJson('/api/tickets', $ticketData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tickets', ["description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."]);
    }
}
