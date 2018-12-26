<?php

namespace App\Unifin\Repositories\Recalls;

use App\Unifin\Repositories\Recalls\Contracts\RecallActionInterface;
use App\Unifin\Repositories\Recalls\Contracts\RecallInterface;
use Illuminate\Http\Request;

class Recall implements RecallInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function make(RecallActionInterface $recallAction)
    {
        $recallAction->generate();
    }
}