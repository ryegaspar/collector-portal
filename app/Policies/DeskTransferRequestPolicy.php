<?php

namespace App\Policies;

use App\Models\Lynx\DeskTransferRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeskTransferRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has permission to delete the adjustment.
     *
     * @param $user
     * @param DeskTransferRequest $deskTransferRequest
     * @return bool
     */
    public function modify($user, DeskTransferRequest $deskTransferRequest)
    {
        if (($user instanceof $deskTransferRequest->requestable_type) && $deskTransferRequest->status == 0 && $deskTransferRequest->requestable_id == $user->id) {
            return true;
        }

        return false;
    }
}
