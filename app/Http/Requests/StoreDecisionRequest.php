<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDecisionRequest extends FormRequest
{

public function authorize(): bool
{
    return $this->user()?->hasAnyRole(['SG','Présidente','Presidente']) ?? false;
}

public function rules(): array
{
    return [
        'type'    => ['required','string','max:100'],
        'contenu' => ['required','string'],
    ];
}

}