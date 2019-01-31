<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Unifin\Traits\LetterRequestCrud;
use Unifin\Traits\Paginate;

class LetterRequestController extends Controller
{
    use Paginate;
    use LetterRequestCrud;

    private $page = 'collector.letter-requests';

    /**
     * LetterRequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }
}
