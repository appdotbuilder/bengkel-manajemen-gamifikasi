<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_type' => 'required|in:ringan,berat',
            'complaint' => 'required|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'assigned_mechanic_id' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.exists' => 'Pelanggan tidak valid.',
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists' => 'Kendaraan tidak valid.',
            'service_type.required' => 'Jenis servis wajib dipilih.',
            'service_type.in' => 'Jenis servis tidak valid.',
            'complaint.required' => 'Keluhan pelanggan wajib diisi.',
            'estimated_cost.numeric' => 'Estimasi biaya harus berupa angka.',
            'estimated_cost.min' => 'Estimasi biaya tidak boleh negatif.',
            'assigned_mechanic_id.exists' => 'Mekanik tidak valid.',
        ];
    }
}