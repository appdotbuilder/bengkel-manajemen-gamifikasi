<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
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
        $vehicleId = $this->route('vehicle')->id;
        
        return [
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicleId,
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|string|max:4',
            'type' => 'required|in:matic,2tak,4tak',
            'engine_capacity' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
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
            'license_plate.required' => 'Nomor polisi wajib diisi.',
            'license_plate.unique' => 'Nomor polisi sudah terdaftar pada kendaraan lain.',
            'brand.required' => 'Merk kendaraan wajib diisi.',
            'model.required' => 'Model kendaraan wajib diisi.',
            'year.required' => 'Tahun kendaraan wajib diisi.',
            'type.required' => 'Jenis kendaraan wajib dipilih.',
            'type.in' => 'Jenis kendaraan tidak valid.',
        ];
    }
}