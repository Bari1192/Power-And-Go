<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use Tests\TestCase;

class Step2_CategoryControllerTest extends TestCase
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

    public function test_can_get_category_category_type(): void
    {
        $category = Category::FirstOrFail();
        $response = $this->get("/api/categories/{$category->id}");
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('category_class', $data, 'a `category_class` nem töltődött be hozzá.');
    }

    public function test_can_get_category_with_power(): void
    {
        $category = Category::FirstOrFail();
        $response = $this->get("/api/categories/{$category->id}");

        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertArrayHasKey('motor_power', $data, 'a `motor_power` nem töltődött be hozzá.');
    }

    public function test_post_fake_category_type_into_db(): void
    {
        $data = [
            "category_class" => "3",
            "motor_power" => 100,
        ];
        $response = $this->post('api/categories', $data);
        $response->assertStatus(201);

        $category=Category::latest('id')->first();
        $this->assertDatabaseHas('categories', [
            "id" => $category->id,
            "category_class" => 3,
            "motor_power" => 100
        ]);
    }
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
