<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PersonFactory extends Factory
{
    // Statikus tárolók az egyedi értékekhez
    private static array $usedEmails = [];
    private static array $usedPhones = [];
    private static array $usedIdCards = [];
    private static array $usedLicenses = [];
    
    // Cache-elt jelszó a teljesítmény javításához
    private static ?string $cachedPassword = null;
    
    public function definition(): array
    {
        // Jelszó cache-elése
        if (self::$cachedPassword === null) {
            self::$cachedPassword = Hash::make('Test1234');
        }
        
        $szulDatum = fake()->dateTimeBetween('-64 years', '-18 years');
        $jogsiKezdete = fake()->dateTimeBetween('-10 years', 'now');
        $jogsiVege = (clone $jogsiKezdete)->modify('+10 years');
        
        $firstname = fake()->lastName();
        $lastname = fake()->firstName();
        
        return [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birth_date' => $szulDatum->format('Y-m-d'),
            'phone' => $this->generateUniquePhone(),
            'email' => $this->generateUniqueEmail($firstname, $lastname),
            'id_card' => $this->generateUniqueIdCard(),
            'driving_license' => $this->generateUniqueLicense(),
            'license_start_date' => $jogsiKezdete->format('Y-m-d'),
            'license_end_date' => $jogsiVege->format('Y-m-d'),
            'person_password' => self::$cachedPassword,
        ];
    }
    
    /**
     * Egyedi e-mail cím generálása
     */
    private function generateUniqueEmail(string $firstname, string $lastname): string
    {
        $maxAttempts = 15;
        $attempt = 0;
        $email = '';
        
        $firstname = strtolower($this->removeAccents($firstname));
        $lastname = strtolower($this->removeAccents($lastname));
        
        do {
            $attempt++;
            $choice = rand(1, 3);
            $domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];
            $domain = $domains[array_rand($domains)];
            
            if ($choice === 1) {
                $email = $firstname . rand(100, 999) . '@' . $domain;
            } elseif ($choice === 2) {
                $email = $firstname . $lastname . rand(10, 99) . '@' . $domain;
            } else {
                $email = fake()->word . rand(1000, 9999) . '@' . $domain;
            }
            
            // Speciális karakterek eltávolítása
            $email = preg_replace('/[^a-z0-9@.]/', '', $email);
            
            // Ha túl sok próbálkozás volt, garantáljuk az egyediséget
            if ($attempt >= 10) {
                $email = substr($email, 0, strpos($email, '@')) . uniqid() . '@' . $domain;
            }
            
        } while (isset(self::$usedEmails[$email]) && $attempt < $maxAttempts);
        
        // Ha még mindig nem egyedi, akkor végső megoldás
        if (isset(self::$usedEmails[$email])) {
            $email = 'user_' . uniqid() . '@example.com';
        }
        
        self::$usedEmails[$email] = true;
        return $email;
    }
    
    /**
     * Egyedi telefonszám generálása
     */
    private function generateUniquePhone(): string
    {
        $maxAttempts = 15;
        $attempt = 0;
        $phone = '';
        
        do {
            $attempt++;
            $prefixes = ['20', '30', '70'];
            $prefix = $prefixes[array_rand($prefixes)];
            $phone = '+36' . $prefix . rand(100, 999) . rand(1000, 9999);
            
        } while (isset(self::$usedPhones[$phone]) && $attempt < $maxAttempts);
        
        // Ha még mindig nem egyedi, akkor végső megoldás
        if (isset(self::$usedPhones[$phone])) {
            $phone = '+36' . rand(10, 99) . uniqid();
        }
        
        self::$usedPhones[$phone] = true;
        return $phone;
    }
    
    /**
     * Egyedi személyi igazolvány szám generálása
     */
    private function generateUniqueIdCard(): string
    {
        $maxAttempts = 15;
        $attempt = 0;
        $idCard = '';
        
        do {
            $attempt++;
            $idCard = strtoupper(fake()->bothify('??######'));
            
            // Ha túl sok próbálkozás, egyedi azonosítót adunk hozzá
            if ($attempt >= 10) {
                $idCard .= substr(uniqid(), -4);
            }
            
        } while (isset(self::$usedIdCards[$idCard]) && $attempt < $maxAttempts);
        
        // Ha még mindig nem egyedi, akkor végső megoldás
        if (isset(self::$usedIdCards[$idCard])) {
            $idCard = 'ID' . strtoupper(uniqid());
        }
        
        self::$usedIdCards[$idCard] = true;
        return $idCard;
    }
    
    /**
     * Egyedi jogosítvány szám generálása
     */
    private function generateUniqueLicense(): string
    {
        $maxAttempts = 15;
        $attempt = 0;
        $license = '';
        
        do {
            $attempt++;
            $license = strtoupper(fake()->bothify('??######'));
            
            // Ha túl sok próbálkozás, egyedi azonosítót adunk hozzá
            if ($attempt >= 10) {
                $license .= substr(uniqid(), -4);
            }
            
        } while (isset(self::$usedLicenses[$license]) && $attempt < $maxAttempts);
        
        // Ha még mindig nem egyedi, akkor végső megoldás
        if (isset(self::$usedLicenses[$license])) {
            $license = 'DL' . strtoupper(uniqid());
        }
        
        self::$usedLicenses[$license] = true;
        return $license;
    }
    
    /**
     * Ékezetek eltávolítása
     */
    private function removeAccents(string $string): string
    {
        $search = ['á', 'é', 'í', 'ó', 'ö', 'ő', 'ú', 'ü', 'ű', 'Á', 'É', 'Í', 'Ó', 'Ö', 'Ő', 'Ú', 'Ü', 'Ű'];
        $replace = ['a', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'u', 'A', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'U'];
        return str_replace($search, $replace, $string);
    }
}
