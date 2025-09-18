<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
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
            'customer_id' => 'sometimes|required|exists:customers,id',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'assigned_mechanic_id' => 'nullable|exists:users,id',
            'service_type' => 'sometimes|required|in:ringan,berat',
            'complaint' => 'sometimes|required|string',
            'diagnosis' => 'nullable|string',
            'work_done' => 'nullable|string',
            'additional_findings' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:menunggu,dikerjakan,pengecekan,selesai',
            'overtime_hours' => 'nullable|numeric|min:0',
            'customer_rating' => 'nullable|integer|min:1|max:5',
            'customer_feedback' => 'nullable|string',
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
            'final_cost.numeric' => 'Biaya akhir harus berupa angka.',
            'final_cost.min' => 'Biaya akhir tidak boleh negatif.',
            'assigned_mechanic_id.exists' => 'Mekanik tidak valid.',
            'status.in' => 'Status tidak valid.',
            'overtime_hours.numeric' => 'Jam lembur harus berupa angka.',
            'overtime_hours.min' => 'Jam lembur tidak boleh negatif.',
            'customer_rating.integer' => 'Rating harus berupa angka.',
            'customer_rating.min' => 'Rating minimal 1.',
            'customer_rating.max' => 'Rating maksimal 5.',
        ];
    }
}