<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Az alábbi nyelvi sorok tartalmazzák az alapértelmezett hibaüzeneteket,
    | amelyeket a validátor osztály használ. Néhány szabály több verzióval is
    | rendelkezik, például a méret szabályok. Ezek az üzenetek itt szabadon
    | testreszabhatók.
    |
    */

    'accepted' => 'A(z) :attribute mezőt el kell fogadni.',
    'accepted_if' => 'A(z) :attribute mezőt el kell fogadni, ha :other értéke :value.',
    'active_url' => 'A(z) :attribute érvényes URL kell, hogy legyen.',
    'after' => 'A(z) :attribute dátumnak :date utáni dátumnak kell lennie.',
    'after_or_equal' => 'A(z) :attribute dátumnak :date utáni vagy azzal egyenlő dátumnak kell lennie.',
    'alpha' => 'A(z) :attribute mező csak betűket tartalmazhat.',
    'alpha_dash' => 'A(z) :attribute mező csak betűket, számokat, kötőjeleket és aláhúzásokat tartalmazhat.',
    'alpha_num' => 'A(z) :attribute mező csak betűket és számokat tartalmazhat.',
    'array' => 'A(z) :attribute mezőnek tömbnek kell lennie.',
    'ascii' => 'A(z) :attribute mező csak egybájtos alfanumerikus karaktereket és szimbólumokat tartalmazhat.',
    'before' => 'A(z) :attribute dátumnak :date előtti dátumnak kell lennie.',
    'before_or_equal' => 'A(z) :attribute dátumnak :date előtti vagy azzal egyenlő dátumnak kell lennie.',
    'between' => [
        'array' => 'A(z) :attribute mezőnek :min és :max elem között kell lennie.',
        'file' => 'A(z) :attribute méretének :min és :max kilobájt között kell lennie.',
        'numeric' => 'A(z) :attribute értékének :min és :max között kell lennie.',
        'string' => 'A(z) :attribute hosszának :min és :max karakter között kell lennie.',
    ],
    'boolean' => 'A(z) :attribute mező csak igaz vagy hamis lehet.',
    'can' => 'A(z) :attribute mező nem tartalmazhat jogosulatlan értéket.',
    'confirmed' => 'A(z) :attribute megerősítés nem egyezik.',
    'contains' => 'A(z) :attribute mezőből hiányzik egy kötelező érték.',
    'current_password' => 'A megadott jelszó helytelen.',
    'date' => 'A(z) :attribute érvényes dátumnak kell lennie.',
    'date_equals' => 'A(z) :attribute dátumnak meg kell egyeznie :date dátummal.',
    'date_format' => 'A(z) :attribute nem felel meg a következő formátumnak: :format.',
    'decimal' => 'A(z) :attribute mezőnek :decimal tizedesjegyet kell tartalmaznia.',
    'declined' => 'A(z) :attribute mezőt el kell utasítani.',
    'declined_if' => 'A(z) :attribute mezőt el kell utasítani, ha :other értéke :value.',
    'different' => 'A(z) :attribute és :other értékeknek különbözőnek kell lenniük.',
    'digits' => 'A(z) :attribute mezőnek :digits számjegyűnek kell lennie.',
    'digits_between' => 'A(z) :attribute mezőnek :min és :max számjegy között kell lennie.',
    'dimensions' => 'A(z) :attribute érvénytelen képméretekkel rendelkezik.',
    'distinct' => 'A(z) :attribute mezőben duplikált érték található.',
    'doesnt_end_with' => 'A(z) :attribute mező nem végződhet a következőkkel: :values.',
    'doesnt_start_with' => 'A(z) :attribute mező nem kezdődhet a következőkkel: :values.',
    'email' => 'A(z) :attribute mező érvényes e-mail cím kell, hogy legyen.',
    'ends_with' => 'A(z) :attribute mezőnek a következők egyikével kell végződnie: :values.',
    'enum' => 'A kiválasztott :attribute érvénytelen.',
    'exists' => 'A kiválasztott :attribute érvénytelen.',
    'extensions' => 'A(z) :attribute mezőnek az alábbi kiterjesztések egyikével kell rendelkeznie: :values.',
    'file' => 'A(z) :attribute mezőnek fájlnak kell lennie.',
    'filled' => 'A(z) :attribute mező kitöltése kötelező.',
    'gt' => [
        'array' => 'A(z) :attribute mezőnek több mint :value elemet kell tartalmaznia.',
        'file' => 'A(z) :attribute méretének nagyobbnak kell lennie, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute értékének nagyobbnak kell lennie, mint :value.',
        'string' => 'A(z) :attribute hosszának nagyobbnak kell lennie, mint :value karakter.',
    ],
    'gte' => [
        'array' => 'A(z) :attribute mezőnek legalább :value elemet kell tartalmaznia.',
        'file' => 'A(z) :attribute méretének legalább :value kilobájt kell lennie.',
        'numeric' => 'A(z) :attribute értékének legalább :value kell lennie.',
        'string' => 'A(z) :attribute hosszának legalább :value karakter kell lennie.',
    ],
    'hex_color' => 'A(z) :attribute mezőnek érvényes hexadecimális színkódnak kell lennie.',
    'image' => 'A(z) :attribute mezőnek képfájlnak kell lennie.',
    'in' => 'A kiválasztott :attribute érvénytelen.',
    'in_array' => 'A(z) :attribute mező nem található meg a(z) :other értékben.',
    'integer' => 'A(z) :attribute mezőnek egész számnak kell lennie.',
    'ip' => 'A(z) :attribute mezőnek érvényes IP-címnek kell lennie.',
    'ipv4' => 'A(z) :attribute mezőnek érvényes IPv4-címnek kell lennie.',
    'ipv6' => 'A(z) :attribute mezőnek érvényes IPv6-címnek kell lennie.',
    'json' => 'A(z) :attribute mezőnek érvényes JSON karakterláncnak kell lennie.',
    'list' => 'A(z) :attribute mezőnek listának kell lennie.',
    'lowercase' => 'A(z) :attribute mezőnek kisbetűsnek kell lennie.',
    'lt' => [
        'array' => 'A(z) :attribute mezőnek kevesebb mint :value elemet kell tartalmaznia.',
        'file' => 'A(z) :attribute méretének kisebbnek kell lennie, mint :value kilobájt.',
        'numeric' => 'A(z) :attribute értékének kisebbnek kell lennie, mint :value.',
        'string' => 'A(z) :attribute hosszának kisebbnek kell lennie, mint :value karakter.',
    ],
    'lte' => [
        'array' => 'A(z) :attribute mezőnek legfeljebb :value elemet kell tartalmaznia.',
        'file' => 'A(z) :attribute méretének legfeljebb :value kilobájt kell lennie.',
        'numeric' => 'A(z) :attribute értékének legfeljebb :value kell lennie.',
        'string' => 'A(z) :attribute hosszának legfeljebb :value karakter kell lennie.',
    ],
    'mac_address' => 'A(z) :attribute mezőnek érvényes MAC-címnek kell lennie.',
    'max' => [
        'array' => 'A(z) :attribute mező legfeljebb :max elemet tartalmazhat.',
        'file' => 'A(z) :attribute mérete nem lehet nagyobb, mint :max kilobájt.',
        'numeric' => 'A(z) :attribute értéke nem lehet nagyobb, mint :max.',
        'string' => 'A(z) :attribute hossza nem lehet több, mint :max karakter.',
    ],
    'max_digits' => 'A(z) :attribute mező nem tartalmazhat több, mint :max számjegyet.',
    'mimes' => 'A(z) :attribute mezőnek az alábbi típusú fájlok egyikének kell lennie: :values.',
    'mimetypes' => 'A(z) :attribute mezőnek az alábbi típusú fájlok egyikének kell lennie: :values.',
    'min' => [
        'array' => 'A(z) :attribute mezőnek legalább :min elemet kell tartalmaznia.',
        'file' => 'A(z) :attribute méretének legalább :min kilobájt kell lennie.',
        'numeric' => 'A(z) :attribute értékének legalább :min kell lennie.',
        'string' => 'A(z) :attribute hosszának legalább :min karakter kell lennie.',
    ],
    'min_digits' => 'A(z) :attribute mezőnek legalább :min számjegyet kell tartalmaznia.',
    'missing' => 'A(z) :attribute mező hiányozhat.',
    'missing_if' => 'A(z) :attribute mező hiányozhat, ha :other értéke :value.',
    'missing_unless' => 'A(z) :attribute mező hiányozhat, kivéve ha :other értéke :value.',
    'missing_with' => 'A(z) :attribute mező hiányozhat, ha a(z) :values jelen van.',
    'missing_with_all' => 'A(z) :attribute mező hiányozhat, ha a(z) :values jelen vannak.',
    'multiple_of' => 'A(z) :attribute mező értékének a(z) :value többszörösének kell lennie.',
    'not_in' => 'A kiválasztott :attribute érvénytelen.',
    'not_regex' => 'A(z) :attribute mező formátuma érvénytelen.',
    'numeric' => 'A(z) :attribute mező szám kell, hogy legyen.',
    'password' => [
        'letters' => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy betűt.',
        'mixed' => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy nagybetűt és egy kisbetűt.',
        'numbers' => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy számot.',
        'symbols' => 'A(z) :attribute mezőnek tartalmaznia kell legalább egy szimbólumot.',
        'uncompromised' => 'A megadott :attribute szerepelt egy adatvédelmi incidensben. Kérjük, válasszon másik :attribute-t.',
    ],
    'present' => 'A(z) :attribute mező jelen kell legyen.',
    'present_if' => 'A(z) :attribute mező jelen kell legyen, ha :other értéke :value.',
    'present_unless' => 'A(z) :attribute mező jelen kell legyen, kivéve ha :other értéke :value.',
    'present_with' => 'A(z) :attribute mező jelen kell legyen, ha a(z) :values jelen van.',
    'present_with_all' => 'A(z) :attribute mező jelen kell legyen, ha a(z) :values jelen vannak.',
    'prohibited' => 'A(z) :attribute mező tiltott.',
    'prohibited_if' => 'A(z) :attribute mező tiltott, ha :other értéke :value.',
    'prohibited_unless' => 'A(z) :attribute mező tiltott, kivéve ha :other értéke :values.',
    'prohibits' => 'A(z) :attribute mező megakadályozza, hogy :other jelen legyen.',
    'regex' => 'A(z) :attribute mező formátuma érvénytelen.',
    'required' => 'A(z) :attribute mező kitöltése kötelező.',
    'required_array_keys' => 'A(z) :attribute mezőnek tartalmaznia kell a következő kulcsokat: :values.',
    'required_if' => 'A(z) :attribute mező kitöltése kötelező, ha :other értéke :value.',
    'required_if_accepted' => 'A(z) :attribute mező kitöltése kötelező, ha :other el van fogadva.',
    'required_if_declined' => 'A(z) :attribute mező kitöltése kötelező, ha :other el van utasítva.',
    'required_unless' => 'A(z) :attribute mező kitöltése kötelező, kivéve ha :other értéke :values.',
    'required_with' => 'A(z) :attribute mező kitöltése kötelező, ha a(z) :values jelen van.',
    'required_with_all' => 'A(z) :attribute mező kitöltése kötelező, ha a(z) :values jelen vannak.',
    'required_without' => 'A(z) :attribute mező kitöltése kötelező, ha a(z) :values nincs jelen.',
    'required_without_all' => 'A(z) :attribute mező kitöltése kötelező, ha a(z) :values egyike sincs jelen.',
    'same' => 'A(z) :attribute és :other mezőknek egyezniük kell.',
    'size' => [
        'array' => 'A(z) :attribute mezőnek :size elemet kell tartalmaznia.',
        'file' => 'A(z) :attribute méretének :size kilobájt kell lennie.',
        'numeric' => 'A(z) :attribute értékének :size kell lennie.',
        'string' => 'A(z) :attribute hosszának :size karakter kell lennie.',
    ],
    'starts_with' => 'A(z) :attribute mezőnek a következőkkel kell kezdődnie: :values.',
    'string' => 'A(z) :attribute mezőnek szövegnek kell lennie.',
    'timezone' => 'A(z) :attribute mezőnek érvényes időzónának kell lennie.',
    'unique' => 'A(z) :attribute már foglalt.',
    'uploaded' => 'A(z) :attribute feltöltése sikertelen.',
    'uppercase' => 'A(z) :attribute mezőnek nagybetűsnek kell lennie.',
    'url' => 'A(z) :attribute mezőnek érvényes URL-nek kell lennie.',
    'ulid' => 'A(z) :attribute mezőnek érvényes ULID-nek kell lennie.',
    'uuid' => 'A(z) :attribute mezőnek érvényes UUID-nek kell lennie.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Itt adhatók meg testreszabott hibaüzenetek az attribútumokhoz, a szabály
    | nevének megadásával. Ez lehetővé teszi az egyedi üzenetek gyors
    | hozzárendelését adott szabályokhoz.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Az alábbi nyelvi sorok az attribútum helyőrzők helyettesítésére szolgálnak
    | barátságosabb nevek használatával, például "E-mail cím" az "email" helyett.
    | Ez segít kifejezőbb üzenetek létrehozásában.
    |
    */

    'attributes' => [
        'user_name' => 'felhasználó név',
        'password' => 'jelszó',
        'id_card' => 'személyigazolvány szám',
        'firstname' => 'vezetéknév',
        'lastname' => 'keresztnév',
        'birth_date' => 'születési dátum',
        'phone' => 'telefonszám',
        'email' => 'e-mail cím',
        'bill_type' => 'számla típusa',
        'user_id' => 'felhasználó azonosító',
        'person_id' => 'személy azonosító',
        'car_id' => 'autó azonosító',
        'total_cost' => 'végösszeg',
        'distance' => 'levezetett táv (km)',
        'parking_minutes' => 'parkolási perc',
        'driving_minutes' => 'vezetési perc',
        'rent_start' => 'bérlés kezdő ideje',
        'rent_close' => 'bérlés záró dátuma',
        'invoice_date' => 'számla kiállítási ideje',
        'invoice_status' => 'számla státusza',
        'category_id' => 'kategória azonosító',
        'equipment_class' => 'felszereltségi besorolás',
        'fleet_id' => 'flotta azonosító',
        'status' => 'állapot státusz',
        'plate' => 'rendszám',
        'odometer' => 'kilométeróra',
        'manufactured' => 'gyártási év',
        'power_kw' => 'aktuális akkumulátor kapacitás (kW)',
        'power_percent' => 'töltöttségi szint (%)',
        'estimated_range' => 'becsült hatótáv (km)',
        'status_name' => 'státusz neve',
        'status_descrip' => 'státusz leírása',
        'category_class' => 'kategória osztály',
        'motor_power' => 'motor teljesítmény (kW)',
        'field' => 'terület',
        'role' => 'munkakör',
        'position' => 'beosztás',
        'salary_type' => 'bérezés típusa',
        'salary' => 'fizetés összege',
        'hire_date' => 'belépés dátuma',
        'manufacturer' => 'gyártó',
        'carmodel' => 'modell',
        'top_speed' => 'maximális sebesség (km/h)',
        'tire_size' => 'szabvány abroncsméret',
        'driving_range' => 'maximális hatótáv (km)',
        'person_password' => 'személyes jelszó',
        'driving_license' => 'vezetési engedély',
        'license_start_date' => 'vezetési engedély kezdete',
        'license_end_date' => 'vezetési engedély vége',
        'sub_name' => 'előfizetés név',
        'sub_monthly' => 'előfizetés havi költség',
        'sub_annual' => 'előfizetés éves költség',
        'status_id' => 'státusz azonosító',
        'description' => 'leírás',
        'password_2_4' => 'jelszó második és negyedik számjegye',
        'account_balance' => 'felhasználói egyenleg',
        'sub_id' => 'előfizetés azonosító',
    ],
];
