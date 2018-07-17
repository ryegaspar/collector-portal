<?php

namespace App\Rules;

use App\DebterPayment;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdjustmentAmount implements Rule
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
        $date = Carbon::parse(request()->date)->toDateString();
        return DebterPayment::where('PAY_DBR_NO', '=', request()->dbr_no)
                ->whereDate('PAY_DATE_O', '=', $date)
                ->where('PAY_AMT', '=', $value)
                ->where('PAY_STATUS', '=', 'T')
                ->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid :attribute or there is no transaction that took place with that amount, date, and debter no.';
    }
}
