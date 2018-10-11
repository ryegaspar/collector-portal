<?php

namespace App\Policies;

use App\Models\Lynx\Collector;
use App\Models\Lynx\LetterRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user has permission to delete the adjustment.
     *
     * @param Collector $collector
     * @param LetterRequest $letterRequest
     * @return bool
     */
    public function collectorModify(Collector $collector, LetterRequest $letterRequest)
    {
        if ($letterRequest->requestable_type == 'App\Models\Lynx\Collector' && $letterRequest->status == 0 && $letterRequest->requestable_id == $collector->id) {
            return true;
        }

        return false;
    }
}
