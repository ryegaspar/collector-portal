<?php

namespace App\Policies;

use App\Models\Lynx\LetterRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has permission to delete the adjustment.
     *
     * @param $user
     * @param LetterRequest $letterRequest
     * @return bool
     */
    public function modify($user, LetterRequest $letterRequest)
    {
        if (($user instanceof $letterRequest->requestable_type) && $letterRequest->status == 0 && $letterRequest->requestable_id == $user->id) {
            return true;
        }

        return false;
    }
}
