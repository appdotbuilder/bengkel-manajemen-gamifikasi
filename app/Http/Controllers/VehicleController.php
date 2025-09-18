<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Store a new vehicle for a customer.
     */
    public function store(StoreVehicleRequest $request, Customer $customer)
    {
        $vehicle = $customer->vehicles()->create($request->validated());

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    /**
     * Update the specified vehicle.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return redirect()->route('customers.show', $vehicle->customer)
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(Vehicle $vehicle)
    {
        $customer = $vehicle->customer;
        $vehicle->delete();

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Kendaraan berhasil dihapus.');
    }
}