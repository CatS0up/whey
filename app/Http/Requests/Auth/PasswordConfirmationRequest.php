<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DataObjects\Auth\PasswordConfirmationData;
use App\Http\Requests\Contracts\DataObjectConvertable;
use App\Validators\User\PasswordValidator;
use Illuminate\Foundation\Http\FormRequest;

class PasswordConfirmationRequest extends FormRequest implements DataObjectConvertable
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

    public function toDataObject(): PasswordConfirmationData
    {
        return PasswordConfirmationData::fromFormRequest($this);
    }
}
