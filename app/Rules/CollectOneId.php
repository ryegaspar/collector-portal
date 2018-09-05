<?php

namespace App\Rules;

use App\Models\Lynx\Admin;
use App\Models\Lynx\Collector;
use Illuminate\Contracts\Validation\Rule;

class CollectOneId implements Rule
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
        $reservedIdCount = collect(config('unifin.collectone_reserved_id'))->filter(function($default) use ($value) {
            return strtolower($default) == $value;
        })->count();

        $activeCollectOneIdCount = Collector::where('active', true)
                ->where('tiger_user_id', $value)
                ->count() +
            Admin::where('active', true)
                ->where('tiger_user_id', $value)
                ->count() +
            $reservedIdCount;

        return $activeCollectOneIdCount == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is already in used';
    }
}
