<?php

namespace App\Rules;

use App\Models\Lynx\LetterRequestType as LetterRequestTypeLynx;
use Illuminate\Contracts\Validation\Rule;

class LetterRequestType implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $letterRequestType = LetterRequestTypeLynx::findOrFail($value);

        return ! ! $letterRequestType->active;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid Letter Request Type';
    }
}
