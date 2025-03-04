<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $totalUsers = 300;
        $chunkSize = 100;
        $createdUsers = 0;

        try {
            DB::beginTransaction();

            for ($offset = 0; $offset < $totalUsers; $offset += $chunkSize) {
                $currentChunkSize = min($chunkSize, $totalUsers - $offset);

                $users = User::factory()->count($currentChunkSize)->make();
                $usersArray = $users->toArray();
                foreach ($usersArray as &$user) {
                    $user['created_at'] = date('Y-m-d H:i:s');
                    $user['updated_at'] = date('Y-m-d H:i:s');
                }

                User::insert($usersArray);
                $createdUsers += $currentChunkSize;
            }
            DB::commit();
            $this->command->info("\tÖsszesen $totalUsers felhasználó sikeresen létrejött.");
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Hiba történt: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
