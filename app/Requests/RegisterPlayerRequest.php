<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the registration form fields required by the assignment.
 */
class RegisterPlayerRequest extends FormRequest
{
    /**
     * Registration is public — no authenticated user is required.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255'],
            // Form field name matches the assignment wording ("Phonenumber")
            'phonenumber' => ['required', 'string', 'max:255'],
        ];
    }
}
