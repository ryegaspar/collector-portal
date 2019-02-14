<?php

namespace App\Rules;

use App\Models\Tiger\DBR;
use Illuminate\Contracts\Validation\Rule;

class DbrNotInHouse implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $dbr = DBR::find($value);

        if (is_null($dbr))
            return true; // let rule 'exists' do its job

        return $dbr->DBR_DESK !== 'HSE';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Accounts in a House desk are not allowed to be requested in this log, instead please follow proper desk transfer protocol to ensure the account is transfered correctly.';
    }
}
