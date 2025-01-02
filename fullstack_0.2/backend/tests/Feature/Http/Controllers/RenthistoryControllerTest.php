<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\RenthistoryController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RenthistoryControllerTest extends TestCase
{
    public function get_all_renthistory_data(): void
    {
        $response = $this->get('/api/renthistories');
        $response->assertStatus(200);
    }
}
