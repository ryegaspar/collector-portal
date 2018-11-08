<?php

namespace App\Unifin\Repositories\Recalls;

interface RecallInterface
{
    public function makeRecallByAssignedDate($client, $assignedDate);

    public function makeRecallByFileGeneric($client, $file, $genericType);
}