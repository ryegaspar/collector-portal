<?php

namespace App\Observers\Lynx;

use App\Models\Lynx\Collector;
use App\Models\Tiger\DSK;
use App\Models\Tiger\USR;

class CollectorObserver
{
    /**
     * Handle the collector "created" event.
     *
     * @param  Collector  $collector
     * @return void
     */
    public function created(Collector $collector)
    {
        USR::makeCollectOneUser($collector);
        DSK::makeCollectOneDesk($collector);
    }

    /**
     * Handle the collector "updated" event.
     *
     * @param Collector $collector
     * @return void
     */
    public function updated(Collector $collector)
    {
        if ($collector->isDirty('group')) {
            USR::findOrFail($collector->tiger_user_id)->update([
                'USR_GROUP' => $collector->group
            ]);
        }
    }

    /**
     * Handle the collector "deleted" event.
     *
     * @param Collector $collector
     * @return void
     */
    public function deleted(Collector $collector)
    {
        //
    }

    /**
     * Handle the collector "restored" event.
     *
     * @param Collector $collector
     * @return void
     */
    public function restored(Collector $collector)
    {
        //
    }

    /**
     * Handle the collector "force deleted" event.
     *
     * @param Collector $collector
     * @return void
     */
    public function forceDeleted(Collector $collector)
    {
        //
    }
}
