<?php

namespace App\Policies;

use App\Adjustment;
use App\USR;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AdjustmentPolicy
{
    use HandlesAuthorization;

    /**
     * determine if the user has permission to delete the adjustment
     *
     * @param USR $usr
     * @param Adjustment $adjustment
     * @return bool
     */
    public function delete(USR $usr, Adjustment $adjustment)
    {
        return $adjustment->desk == $usr->USR_DEF_MOT_DESK;
    }
}
