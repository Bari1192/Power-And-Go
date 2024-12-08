<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParkolasEsteVIPElofizetessel extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_Parkolas_Este_Tiz_Es_Reggel_Het_Kozott_VIP_Elofizetessel(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
