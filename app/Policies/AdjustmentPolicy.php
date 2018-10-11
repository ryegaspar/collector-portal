<?php

namespace App\Policies;

use App\Models\Lynx\Adjustment;
use App\Models\Lynx\Collector;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdjustmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has permission to delete the adjustment.
     *
     * @param Collector $collector
     * @param Adjustment $adjustment
     * @return bool
     */
    public function delete(Collector $collector, Adjustment $adjustment)
    {
        return $adjustment->status == 0 && $adjustment->desk == $collector->desk;
    }
}
