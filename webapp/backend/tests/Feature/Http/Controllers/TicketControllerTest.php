<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Car;
use App\Models\Ticket;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    public function test_can_get_all_tickets(): void
    {
        $response = $this->get('/api/tickets');
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertNotEmpty($data);
    }
    public function test_can_get_ticket_id(): void
    {
        $ticket = Ticket::FirstOrFail();

        $response = $this->get("/api/tickets/{$ticket->id}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('id', $data, 'az `id` nem töltődött be hozzá.');
    }
    public function test_can_get_ticket_description(): void
    {
        $ticket = Ticket::FirstOrFail();
        $response = $this->get("/api/tickets/{$ticket->id}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('description', $data, 'a `description` nem töltődött be hozzá.');
    }
    public function test_can_get_ticket_with_car_id(): void
    {
        $ticket = Ticket::FirstOrFail();
        $response = $this->get("/api/tickets/{$ticket->id}");

        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('car_id', $data, 'a `car_id` nem töltődött be hozzá.');
    }
    public function test_can_get_ticket_with_car_status_id(): void
    {
        $ticket = Ticket::FirstOrFail();
        $response = $this->get("/api/tickets/{$ticket->id}");

        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('status_id', $data, 'a `status_id` nem töltődött be hozzá.');
    }
    public function test_can_get_ticket_with_car_status_status_description(): void
    {
        $ticket = Ticket::FirstOrFail();
        $response = $this->get("/api/tickets/{$ticket->id}");

        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('status_descrip', $data, 'a `status_descrip`rész nem töltődött be hozzá.');
    }
    public function test_can_get_ticket_with_report_date_time(): void
    {
        $ticket = Ticket::FirstOrFail();
        $response = $this->get("/api/tickets/{$ticket->id}");

        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('bejelentve', $data, 'a `bejelentve`rész nem töltődött be hozzá.');
    }

    public function test_can_create_ticket_to_the_first_car()
    {
        $car = Car::FirstOrFail();
        $ticketData = [
            "car_id" => $car->id,
            "status_id" => 6,
            "description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."
        ];

        $response = $this->postJson('/api/tickets', $ticketData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('tickets', ["description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."]);
    }
    public function test_can_delete_ticket_from_the_first_car_by_last_ticket_id(): void
    {

        $car = Car::FirstOrFail();
        $ticket = Ticket::create([
            "car_id" => $car->id,
            "status_id" => 6,
            "description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."
        ]);

        $response = $this->delete("/api/tickets/{$ticket->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_can_update_first_ticket_status_id_two_times(): void
    {
        $ticket = Ticket::FirstOrFail();
        $car=Car::FirstOrFail();
        $ticketData = [
            "car_id" => $car->id,
            "status_id" => 7,
            "description" => "Az autóban dohányoztak. A Dohányzás szagot és hamufoltokat hagyott az autóban. A hátsó üléseket össze is kenték."
        ];

        $response = $this->put("/api/tickets/{$ticket->id}", $ticketData);
        $response->assertStatus(200);

        $secondTicketData=[
            "car_id" => $car->id,
            "status_id" => 1,
            "description" => "Az autó tisztítása megtörtént, újra forgalomba, helyezem. Foglalhatóvá vált."
        ];
        $response = $this->put("/api/tickets/{$ticket->id}", $secondTicketData);
        $response->assertStatus(200);
    }
}
