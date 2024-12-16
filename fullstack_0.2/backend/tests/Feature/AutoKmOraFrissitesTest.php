<?php

namespace Tests\Feature;

use App\Models\Auto;
use App\Models\Elofizetes;
use App\Models\Felhasznalo;
use App\Models\Flotta_tipusok;
use App\Models\LezartBerles;
use Database\Seeders\ArazasSeeder;
use Database\Seeders\ElofizetesSeeder;
use Database\Seeders\FelszereltsegSeeder;
use Database\Seeders\FlottaTipusSeeder;
use Database\Seeders\KategoriaSeeder;
use Database\Seeders\SzemelySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutoKmOraFrissitesTest extends TestCase
{
    // use RefreshDatabase; // Automatikusan tiszta adatbázis minden teszt előtt

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriaSeeder::class);
        $this->seed(FlottaTipusSeeder::class);
        $this->seed(FelszereltsegSeeder::class);
        $this->seed(SzemelySeeder::class);
        $this->seed(ElofizetesSeeder::class);
        $this->seed(ArazasSeeder::class);
    }

    // public function testAutoKmOraFrissites(): void
    // {
    //     # 1. lépés - Tesztadat implementálása
    //     $flottaTipus = Flotta_tipusok::where('gyarto', 'VW')->first();

    //     $felhasznalo = Felhasznalo::factory()->create([
    //         'elofiz_id' => Elofizetes::first()->elofiz_id,
    //     ]);

    //     $auto = Auto::factory()->create([
    //         'km_ora_allas' => 10000,
    //         'flotta_id_fk' => $flottaTipus->flotta_id,
    //         'kategoria_besorolas_fk' => 3,
    //         'felsz_id_fk' => 1,
    //     ]);

    //     $megtettTavolsag = 10;

    //     # 2. lépés - Három lezárt bérlés létrehozása.
    //     for ($i = 1; $i <= 3; $i++) {
    //         LezartBerles::factory()->create([
    //             'auto_azonosito' => $auto->autok_id,
    //             'szemely_id_fk' => $felhasznalo->szemely_id,
    //             'megtett_tavolsag' => $megtettTavolsag,
    //         ]);
    //         $auto->km_ora_allas += $megtettTavolsag;
    //         $auto->save();
    //         $expectedKmOraAllas=10000 + ($megtettTavolsag * $i);
    //         dump([
    //             'Bérlés száma' => $i,
    //             'Várható km_óra_állás' => $expectedKmOraAllas,
    //             'Tényleges km_óra_állás' => $auto->km_ora_allas,
    //         ]);
    //         $this->assertEquals($expectedKmOraAllas, $auto->km_ora_allas, "A Kilóméteróra állása NEM FRISSÜLT a {$i}. bérlés után.");
    //     }
    // }
}
