<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $candidateId = $this->route('candidate')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'passport_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('candidates', 'passport_number')->ignore($candidateId),
            ],
            'passport_expired' => ['required', 'date'],
            'country' => ['required', 'string', 'max:120'],
            'dp_status' => ['required', Rule::in(['done', 'pending'])],
            'medical_status' => ['required', Rule::in(['done', 'not_yet'])],
            'visa_status' => ['required', Rule::in(['process', 'approved', 'rejected'])],
            'ticket_status' => ['required', Rule::in(['booked', 'not_yet'])],
            'departure_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
