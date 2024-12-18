<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\RenthistoryController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RenthistoryControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_renthistory_data(): void
    {
        $response = $this->get('/api/renthistories');
        $response->assertStatus(200);
    }
}
