<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function can_get_all_tickets(): void
    {
        $response = $this->getJson('/api/cars');
        $response->assertStatus(200);
    }

    public function can_create_ticket()
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
    public function delete_ticket(): void
    {
        $ticket = Ticket::create([
            "car_id" => 10,
            "status_id" => 6,
            "description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."
        ]);

        $response = $this->delete("/api/tickets/{$ticket->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }
    
    
    
    public function update_ticket_data(): void
    {
        $ticket = Ticket::create([
            "description" => "Az autóban dohányoztak.",
            "car_id" => 100,
            "status_id" => 6,
        ]);
        $updatedData = [
            "description" => "Az autó tiszta állapotban került vissza a flottába. Az autó 1-es státuszba került, bérelhetővé vált.",
            "car_id" => 100,
            "status_id" => 1,
        ];
        $response = $this->put("/api/tickets/{$ticket->id}", $updatedData);
    
        ### Ellenőrizzük a válasz státuszt
        $response->assertStatus(200);
    
        ### A válaszban a frissített adatok szerepelnek?
        $response->assertJson([
            'data' => [
                'id' => $ticket->id,
                "description" => "Az autó tiszta állapotban került vissza a flottába. Az autó 1-es státuszba került, bérelhetővé vált.",
                'car_id' => 100,
                'status_id' => 1,
            ]
        ]);
    
        ### Az adatbázisban is, frissült a rekord?
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            "description" => "Az autó tiszta állapotban került vissza a flottába. Az autó 1-es státuszba került, bérelhetővé vált.",
            'car_id' => 100,
            'status_id' => 1,
        ]);
    }
}
