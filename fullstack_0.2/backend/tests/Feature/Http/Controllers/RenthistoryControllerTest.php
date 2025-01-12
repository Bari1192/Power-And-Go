<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class RenthistoryControllerTest extends TestCase
{
    public function get_all_renthistory_data(): void
    {
        $response = $this->get('/api/renthistories');
        $response->assertStatus(200);
    }
}
