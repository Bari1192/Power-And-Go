<?php

namespace Database\Factories;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;
    private const TICKET_TYPES = [
        'cleaning' => 6,
        'malfunction' => 5,
        'damage' => 5,
        'accident' => 4
    ];
    private array $commentsByType = [
        'cleaning' => [
            "Az autó belsejében kiömlött kávé foltok láthatók a középkonzolon és az üléseken.",
            "A hátsó ülésen ételmaradékot és zsíros foltokat találtak.",
            "A bérlő jelezte, hogy az autó szőnyege sáros és nedves.",
            "Az autó ablakai belülről erősen párásak és foltosak.",
            "Az autó külső felülete tele van sárral és portól.",
            "Az autó belső tere kellemetlen szagot áraszt.",
            "A kormánykerék és a váltógomb ragacsos, tisztításra szorul.",
            "A csomagtérben szétszóródott szemét található.",
            "Az autó ülésein szennyezett takarók és homok láthatók.",
            "A műszerfalon vastag porréteg található."
        ],
        'malfunction' => [
            'Anyósülés oldali ajtó zörög.',
            'Ülésfűtés meghibásodás.',
            'Guminyomás érzékelő hibát jelez.',
            'Fékek hangosak, vizsgálatra szorulnak.'
        ],
        'damage' => [
            "Bal első sárvédőn karcolások.",
            "Sérült biztonsági övcsat a hátsó ülésen.",
            "Jobb oldali visszapillantó tükör letört.",
            "Központi zár meghibásodás.",
            "Hátsó lökhárító karcolások és horpadások.",
            "Tetőkárpit leszakadás.",
            "Kormánykerék borítás kopott és repedt.",
            "Hiányzó dísztárcsa.",
            "Cigarettanyomok a hátsó ülésen.",
            "Tanksapka sérülés."
        ],
        'accident' => [
            "Kereszteződésben történt ütközés piros lámpánál.",
            "Parkolás közben okozott kisebb karcolások.",
            "Hátulról történt ütközés.",
            "Oszlopnak ütközés manőverezés közben.",
            "Szalagkorlátnak ütközés kanyarban.",
            "Ütközés nem megfelelő követési távolság miatt.",
            "Előzés közbeni ütközés.",
            "Megcsúszás miatti árokba csúszás.",
            "Körforgalomban történt oldalsó ütközés.",
            "Parkolás közben kerítésnek ütközés."
        ]
    ];

    public function definition(): array
    {
        $type = fake()->randomElement(array_keys(self::TICKET_TYPES));
        return [
            'car_id' => fake()->numberBetween(1, 50),
            'status_id' => self::TICKET_TYPES[$type],
            'description' => fake()->randomElement($this->commentsByType[$type]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s') 
        ];
    }
    public function cleaning()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => self::TICKET_TYPES['cleaning'],
                'description' => fake()->randomElement($this->commentsByType['cleaning'])
            ];
        });
    }
    public function malfunction()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => self::TICKET_TYPES['malfunction'],
                'description' => fake()->randomElement($this->commentsByType['malfunction'])
            ];
        });
    }
    public function damage()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => self::TICKET_TYPES['damage'],
                'description' => fake()->randomElement($this->commentsByType['damage'])
            ];
        });
    }

    public function accident()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id' => self::TICKET_TYPES['accident'],
                'description' => fake()->randomElement($this->commentsByType['accident'])
            ];
        });
    }
}
