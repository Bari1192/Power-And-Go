<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    public function index():JsonResource
    {
        $employees=Employee::all();
        return EmployeeResource::collection($employees);
    }
    /**
     */
    public function store(StoreEmployeeRequest $request)
    {
        $data=$request->validated();
        $employee=Employee::create($data);
        return new EmployeeResource($employee);
    }
    /**
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);
    }
    /**
     */
    public function update(StoreEmployeeRequest $request, Employee $employee)
    {
        $data=$request->validated();
        $employee->update($data);
        return new EmployeeResource($employee);
    }
    /**
     */
    public function destroy(Employee $employee):Response
    {
        return ($employee->delete()? response()->noContent() : abort(500));
    }
}
