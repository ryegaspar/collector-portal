<?php

namespace App\Rules;

use App\Models\Lynx\DeskTransferRequest;
use Illuminate\Contracts\Validation\Rule;

class DbrNotForReviewInDeskTransferRequest implements Rule
{
    protected $id;

    /**
     * Create a new rule instance.
     *
     * @param null $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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
        $dtr = DeskTransferRequest::where('dbr_no', $value)->where('status', 0);

        if (!is_null($this->id))
            $dtr->where('id', '<>', $this->id);

        return $dtr->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This account is currently under review for desk transfer.';
    }
}
