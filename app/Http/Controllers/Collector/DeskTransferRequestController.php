<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Unifin\Traits\DeskTransferRequestCrud;
use Unifin\Traits\Paginate;

class DeskTransferRequestController extends Controller
{
    use Paginate;
    use DeskTransferRequestCrud;

    private $page = 'collector.desk-transfer-requests';

    /**
     * DeskTransferRequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }




}
