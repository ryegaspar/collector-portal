<?php

namespace App\Rules;

use App\Models\Lynx\Collector;
use Illuminate\Contracts\Validation\Rule;

class CollectorId implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $activeCollectorIdCount = Collector::where('active', true)->where('tiger_user_id', $value)->count();

        return $activeCollectorIdCount == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute specified is already in used';
    }
}
