<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DataObjects\Auth\LoginData;
use App\Http\Requests\Contracts\DataObjectConvertable;
use App\Validators\Auth\LoginValidator;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest implements DataObjectConvertable
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(AuthManager $authManager): bool
    {
        return ! $authManager->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return (new LoginValidator())->rules();
    }

    public function toDataObject(): LoginData
    {
        return LoginData::fromFormRequest($this);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'remember_me' => $this->boolean('remember_me'),
        ]);
    }
}
