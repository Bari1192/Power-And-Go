<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    public function index(): JsonResource
    {
        $subs = Subscription::all();
        return SubscriptionResource::collection($subs);
    }
    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $sub = Subscription::create($data);
        return new SubscriptionResource($sub);
    }

    public function show(Subscription $subscription): JsonResource
    {
        return new SubscriptionResource($subscription);
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $data = $request->validated();
        $subscription->update($data);
        return new SubscriptionResource($subscription);
    }

    public function destroy(Subscription $subscription): Response
    {
        return ($subscription->delete() ? response()->noContent() : abort(500));
    }
}
