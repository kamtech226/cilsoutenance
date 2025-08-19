<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSessionRequest extends FormRequest
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
     return [
        'code'         => ['required','string','max:50','unique:training_sessions,code'],
        'theme'        => ['required','string','max:255'],
        'date_session' => ['required','date'],
        'lieu'         => ['required','string','max:255'],
    ];
    }
}
