<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'in:general_inquiry,catering_events,feedback,partnership,media_press'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}