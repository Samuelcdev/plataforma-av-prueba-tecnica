<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests;

use App\Domain\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

final class GetOperativesRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:150'],
            'page' => ['nullable', 'integer', 'min:1'],
            'total' => ['nullable', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator->errors()->first());
    }
}
