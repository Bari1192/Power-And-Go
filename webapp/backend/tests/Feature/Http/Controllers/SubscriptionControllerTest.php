<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Subscription;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{
    public function test_get_all_subscription_data(): void
    {
        $response = $this->get('/api/subscriptions');
        $response->assertStatus(200);

        $data = $response->json('data');
        $response = $this->assertNotEmpty($data);
    }
    public function test_get_random_subscription_data(): void
    {
        $response = $this->get('/api/subscriptions');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $randomObject = $data[array_rand($data)];
        $this->assertArrayHasKey('elofiz_id', $randomObject);

        $response = $this->get("/api/subscriptions/{$randomObject['elofiz_id']}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['elofiz_id' => $randomObject['elofiz_id']]);
    }
    public function test_array_has_every_key_in_random_subscription_data(): void
    {
        $response = $this->get('/api/subscriptions');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $randomObject = $data[array_rand($data)];

        foreach (['elofiz_id', 'elofiz_nev', 'havi_dij', 'eves_dij'] as $key) {
            $this->assertArrayHasKey($key, $randomObject);
        }
    }
    public function test_post_test_subscription_data(): void
    {
        $sampleData = [
            "elofiz_nev" => "teszt_" . uniqid(),
            "havi_dij" => 8888,
            "eves_dij" => 8888
        ];
        $response = $this->postJson('/api/subscriptions', $sampleData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('subscriptions', $sampleData);
        $response->assertJsonFragment($sampleData);
    }
    public function test_can_modify_subscription_data(): void
    {
        $subscription = Subscription::latest('id')->first();

        $this->assertDatabaseHas('subscriptions', [
            "id" => $subscription->id,
            "elofiz_nev" => $subscription->elofiz_nev,
            "havi_dij" => $subscription->havi_dij,
            "eves_dij" => $subscription->eves_dij,
        ]);

        $updatedData = [
            "elofiz_nev" => "Test1234_" . uniqid(),
            "havi_dij" => 4444,
            "eves_dij" => 8888,
        ];

        $response = $this->putJson("/api/subscriptions/{$subscription->id}", $updatedData);
        $response->assertStatus(200);

        $response->assertJsonFragment(array_merge($updatedData, [
            "elofiz_id" => $subscription->id,
        ]));

        $this->assertDatabaseHas('subscriptions', array_merge($updatedData, [
            "id" => $subscription->id,
        ]));
    }
    public function test_delete_subscription(){
        $latestSubscription = Subscription::latest('id')->first();

        $response = $this->delete("api/subscriptions/{$latestSubscription->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing(
            'subscriptions',
            [
                'id' => $latestSubscription->id,
            ]
        );
    }
}
