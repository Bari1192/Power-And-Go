<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $subs = Subscription::all();
        return new SubscriptionResource($subs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $data = $request->validated();
        $sub = Subscription::create($data);
        return new SubscriptionResource($sub);
    }

    /**
     * Display the specified resource.
     */
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
