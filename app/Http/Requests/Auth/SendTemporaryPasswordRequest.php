<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Validators\User\EmailForSendValidator;
use Illuminate\Foundation\Http\FormRequest;

class SendTemporaryPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ! auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return (new EmailForSendValidator())->rules();
    }
}
