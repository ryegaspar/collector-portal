<?php

namespace App\Rules;

use App\Adjustment;
use App\DebterPayment;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdjustmentAmount implements Rule
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
        $date = Carbon::parse(request()->date)->toDateString();

        $debterPayment = DebterPayment::where('PAY_DBR_NO', '=', request()->dbr_no)
            ->whereDate('PAY_DATE_O', '=', $date)
            ->where('PAY_AMT', '=', $value)
            ->where('DESK', '!=', Auth::user()->USR_DEF_MOT_DESK)
            ->where('PAY_STATUS', '=', 'T')
            ->count();

        $adjustment = Adjustment::where('dbr_no', '=', request()->dbr_no)
            ->whereDate('date', '=', $date)
            ->where('amount', '=', $value)
            ->where('desk', '=', Auth::user()->USR_DEF_MOT_DESK)
            ->count();

        return $debterPayment > 0 && $debterPayment > $adjustment;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The account is not eligible for an adjustment<br><br>Possible Reasons for Ineligibility:<br>1)	There is no transaction for that date in that amount.<br>2)	Payment is already in your desk.<br>3)	Exceeded number of adjustment entries for this account.';
    }
}
