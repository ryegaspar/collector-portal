<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdjustmentDate implements Rule
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
        $dateNow = Carbon::now();
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $dateGiven = Carbon::parse($value);

        if ($dateNow < Carbon::parse(Auth::user()->start_date))
            return false;

        if ($dateNow->day > 5) {
            return $dateGiven >= $firstDayOfMonth && $dateGiven <= $dateNow ;
        } else {
            $lastMonth = Carbon::parse("first day of last month")->startOfMonth();

            return $dateGiven >= $lastMonth && $dateGiven <= $dateNow;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute specified is not a valid date for adjustment';
    }
}
