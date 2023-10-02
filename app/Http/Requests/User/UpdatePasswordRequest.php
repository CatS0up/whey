<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\DataObjects\User\UpdatePasswordData;
use App\Validators\User\PasswordValidator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return (new PasswordValidator())->rules();
    }

    public function toDataObject(): UpdatePasswordData
    {
        return UpdatePasswordData::fromFormRequest($this);
    }
}
