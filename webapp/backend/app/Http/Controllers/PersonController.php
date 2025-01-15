<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class PersonController extends Controller
{
    public function index(): JsonResource
    {
        $persons = Person::all();
        return PersonResource::collection($persons);
    }

    public function store(StorePersonRequest $request)
    {
        $data = $request->validated();
        $szemely = Person::create($data);
        return new PersonResource($szemely);
    }

    public function show(Person $person)
    {
        return new PersonResource($person);
    }

    public function update(UpdatePersonRequest $request, Person $person)
    {
        $data=$request->validated();
        $person->update($data);
        return new PersonResource($person);
    }

    public function destroy(Person $person):Response
    {
        return ($person->delete()) ? response()->noContent() : abort(500);
    }
}
