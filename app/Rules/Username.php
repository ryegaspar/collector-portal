<?php

namespace App\Rules;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;

class Username implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
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
         return preg_match('/^(?=.{6,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid username';
    }
}
