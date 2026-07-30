<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();

        return [
            'message' => 'Success',
            'data' => $customers,
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return [
            'message' => 'Customer created',
            'data' => $customer,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return [
            'message' => 'Success',
            'data' => $customer,
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return [
            'message' => 'Customer updated',
            'data' => $customer,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return [
            'message' => 'Customer deleted',
        ];
    }
}
