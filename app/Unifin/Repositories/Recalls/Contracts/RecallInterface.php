<?php

namespace App\Unifin\Repositories\Recalls\Contracts;

interface RecallInterface
{
    public function make(RecallActionInterface $recallAction);
}