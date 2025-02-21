<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::factory()->cleaning()->count(50)->create();
        Ticket::factory()->malfunction()->count(50)->create();
        Ticket::factory()->damage()->count(50)->create();
        Ticket::factory()->accident()->count(50)->create();
    }
}
