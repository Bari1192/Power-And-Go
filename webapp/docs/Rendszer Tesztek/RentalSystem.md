# RentalSystemTest

## Áttekintés

A `RentalSystemTest` osztály felelős a bérlési rendszer integrációs tesztjeinek végrehajtásáért. A tesztosztály a `DatabaseTransactions` trait-et használja, ami biztosítja, hogy minden teszt tiszta adatbázis környezetben fusson.

## Osztály Struktúra

### Privát Tulajdonságok

```bash
private Car $testCar;
private User $testUser;
private Person $testPerson;
private Price $testPrice;
private Category $testCategory;
private Fleet $testFleet;
private Equipment $testEquipment;
private CarRefreshService $carRefreshService;
```

### Inicializálás

A `setUp()` metódus inicializálja a tesztkörnyezetet:

```bash
protected function setUp(): void
{
    parent::setUp();
    $this->carRefreshService = new CarRefreshService();
    $this->createTestData();
}
```

## Tesztadatok Generálása

### `createTestData()` Metódus

A metódus létrehozza a tesztekhez szükséges összes adatot:

1. **Flotta Létrehozása**:
```bash
$testFleet = Fleet::where('manufacturer', 'VW')
    ->where('carmodel', 'e-up!')
    ->first();
```

2. **Kategória Létrehozása**:
```php
$testCategory = Category::create([
    'category_class' => 1,
    'motor_power' => 18,
]);
```

3. **Felszereltség Létrehozása**:
```php
$testEquipment = Equipment::create([
    'reversing_camera'       => true,
    'lane_keep_assist'       => true,
    'adaptive_cruise_control' => true,
    'parking_sensors'        => true,
    'multifunction_wheel'    => true
]);
```

4. **Teszt Autó Létrehozása**:
```php
$testCar = Car::factory()->create([
    'fleet_id'        => 1,
    'category_id'     => 1,
    'equipment_class' => 1,
    'status'          => 1,
    'power_percent'   => 80.00,
    'power_kw'        => 60.0,
    'estimated_range' => 320,
    "plate"           => fake()->regexify('[U-Z]{4}[1-9]{3}'),
    'odometer'        => 10000,
    'manufactured'    => 2023
]);
```

5. **Személy Létrehozása**:
```php
$testPerson = Person::factory()->create([
    "person_password" => fake()->numberBetween(11111111, 87654321),
    "id_card"         => fake()->unique()->regexify('[V-Z]{2}[1-9]{1}[0-9]{5}'),
    "firstname"       => fake()->firstName(),
    "lastname"        => fake()->lastName(),
    "birth_date"      => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
    "phone"           => "+3630" . fake()->regexify('[0-9]{7}'),
    "email"           => fake()->unique()->lexify('??????????@gmail.com'),
]);
```

6. **Felhasználó Létrehozása**:
```php
$testUser = User::factory()->create([
    'person_id'         => $testPerson->id,
    'user_name'         => $testPerson['person_password'],
    'password'          => $testPerson[1] . $testPerson[3],
    'password_2_4'      => $testPerson[1] . $testPerson[3],
    'account_balance'   => 0,
    'sub_id'            => 1,
]);
```

7. **Árazás és Napi Bérlés Adatok Lekérése**:
```php
$testPrice = Price::where('category_class', $testCar->category_id)
    ->where('sub_id', $testUser->sub_id)
    ->firstOrFail();

$testDailyPrice = Dailyrental::where('prices_id', $testUser->sub_id)
    ->where('category_class', $testCar->category_id)
    ->firstOrFail();
```

## Segédmetódusok

### Időszámítás

A `calculateTimes()` metódus a bérlés időtartamának különböző egységeit számolja ki:

```php
private function calculateTimes(DateTime $start, DateTime $end): array
{
    $totalSeconds = $end->getTimestamp() - $start->getTimestamp();
    return [
        'seconds' => $totalSeconds,
        'minutes' => floor($totalSeconds / 60),
        'hours' => floor($totalSeconds / 3600),
        'days' => floor($totalSeconds / (24 * 3600)),
        'remainingHours' => floor(($totalSeconds % (24 * 3600)) / 3600),
        'remainingMinutes' => floor(($totalSeconds % 3600) / 60)
    ];
}
```

## Használt Modellek és Szolgáltatások

- **Models**:
  - `Car`
  - `Category`
  - `Dailyrental`
  - `Equipment`
  - `Fleet`
  - `Person`
  - `Price`
  - `User`

- **Services**:
  - `CarRefreshService`

- **Factories**:
  - `RenthistoryFactory`

## Függőségek

- Laravel TestCase
- DatabaseTransactions trait
- Faker library
- DateTime

## Tesztelési Környezet

A tesztosztály a következő környezeti beállításokat használja:

1. **Adatbázis Tranzakciók**: Minden teszt után visszaállítja az eredeti állapotot
2. **Factory Pattern**: A modellek létrehozásához factory-kat használ
3. **Fake Data**: A Faker library-t használja a tesztadatok generálásához

## Best Practices

1. **Tiszta Környezet**: Minden teszt tiszta környezetben fut
2. **Izolált Tesztek**: A tesztek egymástól függetlenül futtathatók
3. **Valósághű Adatok**: A generált adatok a valós használati eseteket tükrözik
4. **Könnyen Karbantartható**: A tesztadatok létrehozása központosított

## Hibakezelés

A gyakori hibaesetek és megoldásaik:

1. **Flotta nem található**:
   - Ellenőrizze a seeder futását
   - Vizsgálja meg a fleet tábla tartalmát

2. **Árazási adatok hiánya**:
   - Ellenőrizze a Price és Dailyrental táblák tartalmát
   - Vizsgálja meg a kapcsolódó seedereket

3. **Felhasználói adatok duplikációja**:
   - A unique constraintek ellenőrzése
   - A factory beállítások vizsgálata
