<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RecallClassExists implements Rule
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
        $class = '\\App\\Unifin\\Repositories\\Recalls\\' . ucfirst(strtolower(request()->client)) . 'Recall';

        return class_exists($class);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No defined client specific method for this client';
    }
}
