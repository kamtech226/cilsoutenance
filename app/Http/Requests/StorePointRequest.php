<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePointRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        /** @var \App\Models\OrdreDuJour|null $odj */
        $odj  = $this->route('odj');

        if (!$user || !$odj) {
            return false;
        }

        // Passe par la policy (et **pas** de return prématuré)
        return $user->can('create', [\App\Models\PointODJ::class, $odj]);
    }

    public function rules(): array
    {
        return [
            'titre'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'priorite'    => ['nullable','integer','between:1,5'],
        ];
    }
}
