<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function test_can_get_all_categories(): void
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(200);

        $data = $response->json('data');

        $response = $this->assertNotEmpty($data);
    }

    # Objektumon belül -> id renderelődik-e
    public function test_can_get_category_id(): void
    {
        $category = Category::FirstOrFail();

        $response = $this->get("/api/categories/{$category->id}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('id', $data, 'az `id` nem töltődött be hozzá.');
    }

    # Objektumon belül -> kat.besorolása
    public function test_can_get_category_category_type(): void
    {
        $category = Category::FirstOrFail();
        $response = $this->get("/api/categories/{$category->id}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('kat_besorolas', $data, 'a `kat_besorolas` nem töltődött be hozzá.');
    }

    # Objektumon belül -> teljesítmény
    public function test_can_get_category_with_power(): void
    {
        $category = Category::FirstOrFail();
        $response = $this->get("/api/categories/{$category->id}");

        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('teljesitmeny', $data, 'a `teljesitmeny` nem töltődött be hozzá.');
    }

    # POST
    public function test_post_fake_category_type_into_db(): void
    {
        $data = [
            "kat_besorolas" => "3",
            "teljesitmeny" => 100,
        ];
        $response = $this->post('api/categories', $data);
        $response->assertStatus(201);

        $category=Category::latest('id')->first();
        $this->assertDatabaseHas('categories', [
            "id" => $category->id,
            "kat_besorolas" => 3,
            "teljesitmeny" => 100
        ]);
    }
    #DELETE
    public function test_delete_previous_category_type_from_db(): void
    {
        $category = Category::latest('id')->first();

        $response = $this->delete("api/categories/{$category->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing(
            'categories',
            [
                'id' => $category->id,
            ]
        );
    }
}
