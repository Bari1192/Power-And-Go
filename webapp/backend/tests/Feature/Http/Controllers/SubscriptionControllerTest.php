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
        $this->assertArrayHasKey('sub_id', $randomObject);

        $response = $this->get("/api/subscriptions/{$randomObject['sub_id']}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['sub_id' => $randomObject['sub_id']]);
    }
    public function test_array_has_every_key_in_random_subscription_data(): void
    {
        $response = $this->get('/api/subscriptions');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);

        $randomObject = $data[array_rand($data)];

        foreach (['sub_id', 'sub_name', 'sub_monthly', 'sub_annual'] as $key) {
            $this->assertArrayHasKey($key, $randomObject);
        }
    }
    public function test_post_test_subscription_data(): void
    {
        $sampleData = [
            "sub_name" => "teszt_" . uniqid(),
            "sub_monthly" => 8888,
            "sub_annual" => 8888
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
            "sub_name" => $subscription->sub_name,
            "sub_monthly" => $subscription->sub_monthly,
            "sub_annual" => $subscription->sub_annual,
        ]);

        $updatedData = [
            "sub_name" => "Test1234_" . uniqid(),
            "sub_monthly" => 4444,
            "sub_annual" => 8888,
        ];

        $response = $this->putJson("/api/subscriptions/{$subscription->id}", $updatedData);
        $response->assertStatus(200);

        $response->assertJsonFragment(array_merge($updatedData, [
            "sub_id" => $subscription->id,
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
