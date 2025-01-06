<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $randomTisztitasKomment = [
            "Az autó belsejében kiömlött kávé foltok láthatók a középkonzolon és az üléseken.",
            "A hátsó ülésen ételmaradékot és zsíros foltokat találtak.",
            "A bérlő jelezte, hogy az autó szőnyege sáros és nedves, valószínűleg esős idő miatt.",
            "Az autó ablakai belülről erősen párásak és foltosak, nehezítve a kilátást.",
            "Az autó külső felülete tele van sárral és portól, különösen a kerékjáratok körül.",
            "Az autó belső tere kellemetlen szagot áraszt, valószínűleg az előző bérlő által otthagyott ételmaradék miatt.",
            "A bérlő jelezte, hogy a kormánykerék és a váltógomb ragacsos, tisztításra szorul.",
            "A csomagtérben szétszóródott szemét és kellemetlen szag található.",
            "Az autó ülésein szennyezett takarók és homok láthatók, valószínűleg strandolás után.",
            "A bérlő szerint az autó műszerfalán vastag porréteg található, amit az előző bérlő nem takarított le."
        ];
        $randomMeghibasodasKomment = [
            'Az ügyfél hibát jelzett az anyósülés oldali ajtóval kapcsolatban. Zörög valami benne.',
            'Az autóban az ülésfütés kapcsológombjának benyomásakor felvillan, de automatikusan kikapcsol utána.',
            'Az autó guminyomás érzékelője jelzett. Külsérelmi nyomot a bérlő nem talált, látott.',
            'A fékek hangosak, vizsgálatra szorulnak.',
        ];
        $randomRongalasKomment = [
            "A bérlő jelezte, hogy az autó bal első sárvédőjén karcolások találhatók, amelyek korábban nem voltak ott.",
            "Az autó belsejében a bérlő sérült biztonsági övcsatot talált a hátsó ülésen.",
            "A bérlő bejelentette, hogy az autó jobb oldali visszapillantó tükrét letörték, valószínűleg parkolás közben.",
            "A központi zár nem működött, ezért a bérlő kénytelen volt manuálisan zárni az ajtókat.",
            "A bérlő jelezte, hogy az autó hátsó lökhárítóját megkarcolták, és néhány helyen horpadásokat talált.",
            "Az autó tetőkárpitja leszakadt, amit a bérlő az átvételkor észlelt.",
            "A kormánykerék borítása megkopott és repedezett volt az autó visszaadása után.",
            "Az autó egyik keréktárcsájáról hiányzik a dísztárcsa, amit a bérlő jelezett.",
            "A bérlő észrevette, hogy az autó hátsó ülésén cigarettanyomok vannak, bár nem dohányzott az autóban.",
            "Az autó tanksapkáját leszakították, amit a bérlő visszahozáskor jelentett."
        ];
        $randomBalesetKomment = [
            "A felhasználó figyelmetlensége miatt nem vette észre a piros lámpát, és összeütközött a kereszteződésben áthaladó járművel.",
            "Parkolás közben a felhasználó nem figyelt eléggé, és nekiütközött a mellette lévő autónak, kisebb karcolásokat okozva az ajtaján.",
            "Hirtelen fékezés miatt a felhasználó mögött haladó jármű nem tudott időben megállni, és hátulról nekiütközött.",
            "Egy szűk utcában történő manőverezés során a felhasználó az autó jobb oldalával egy oszlopnak ütközött.",
            "A felhasználó túlságosan gyorsan haladt egy kanyarban, elvesztette az uralmát a jármű felett, és az út menti szalagkorlátnak ütközött.",
            "A felhasználó nem tartotta be a megfelelő követési távolságot, és az előtte hirtelen fékező autónak ütközött.",
            "A felhasználó egy előzés során nem figyelte a szembejövő forgalmat, és összeütközött egy szabályosan érkező autóval.",
            "Az autó egy csúszós útszakaszon megcsúszott, és az árokba sodródott, anyagi károkat okozva.",
            "A felhasználó nem vette észre a körforgalomba érkező másik járművet, és oldalról nekiütközött.",
            "A felhasználó parkolóhelyet keresve túl közel ment egy kerítéshez, és megrongálta a jármű bal hátsó részét."
        ];
        for ($i = 0; $i < 50; $i++) {
            DB::table('tickets')->insert([
                'car_id' => fake()->numberBetween(1, 50), ## első 50 kocsira random.
                'status_id' => 6,
                'description' => $randomTisztitasKomment[array_rand($randomTisztitasKomment)],
                'bejelentve' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        for ($i = 0; $i < 50; $i++) {
            DB::table('tickets')->insert([
                'car_id' => fake()->numberBetween(1, 50), ## első 50 kocsira random.
                'status_id' => 5,
                'description' => $randomMeghibasodasKomment[array_rand($randomMeghibasodasKomment)],
                'bejelentve' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        for ($i = 0; $i < 50; $i++) {
            DB::table('tickets')->insert([
                'car_id' => fake()->numberBetween(1, 50), ## első 50 kocsira random.
                'status_id' => 5,
                'description' => $randomRongalasKomment[array_rand($randomRongalasKomment)],
                'bejelentve' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        for ($i = 0; $i < 50; $i++) {
            DB::table('tickets')->insert([
                'car_id' => fake()->numberBetween(1, 50), ## első 50 kocsira random.
                'status_id' => 4,
                'description' => $randomBalesetKomment[array_rand($randomBalesetKomment)],
                'bejelentve' => now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
