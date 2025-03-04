<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['cleaning', 'malfunction', 'damage', 'accident'];
        $count = 50;
        $chunkSize = 100;

        $formatted_date = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($types as $type) {
            $tickets = [];
            $records = Ticket::factory()->{$type}()->count($count)->make();
            foreach ($records as $ticket) {
                $ticketArray = $ticket->toArray();
                $ticketArray['created_at'] = $formatted_date;
                $tickets[] = $ticketArray;
            }
            foreach (array_chunk($tickets, $chunkSize) as $chunk) {
                Ticket::insert($chunk);
            }
        }
    }
}
