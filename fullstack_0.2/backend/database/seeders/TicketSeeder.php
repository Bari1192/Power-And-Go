<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $randomKomment = [
            'Az autó piszkos, a galambok megtámadták.',
            'Az ügyfél hibát jelzett az anyósülés oldali ajtóval kapcsolatban. Zörög valami benne.',
            'Az autóban az előző bérlő dohányzott. A lábtörlőn hamu és piszok. A Bérlő az autót nagyjából 30 perc múlva zárja.',
            'Az autóban az előző bérlő dohányzott. Kellemetlen szag és csikkek az első ülésen.',
            'Az autóban az ülésfütés kapcsológombjának benyomásakor felvillan, de automatikusan kikapcsol utána.',
            'Az autó guminyomás érzékelője jelzett. Külsérelmi nyomot a bérlő nem talált, látott.',
            'A fékek hangosak, vizsgálatra szorulnak.',
            'Bérlő bejelentette, hogy a telefontartó elemet letörték az autóról.',
            'Az autó nem nyílt ki az alkalmazás indításakor. Zárását csak bejelentéssel tudta megoldani.',
        ];
        for ($i = 0; $i < 100; $i++) {
            DB::table('tickets')->insert([
                'car_id' => fake()->numberBetween(1, 50), ## első 50 kocsira random.
                'status_id' => fake()->numberBetween(1, 6),
                'description' => $randomKomment[array_rand($randomKomment)],
                'bejelentve' => now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
