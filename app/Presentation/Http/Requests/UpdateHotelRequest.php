<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use App\Domain\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nit' => ['required', 'string', 'max:20'],
            'document_type' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator->errors()->first());
    }
}
