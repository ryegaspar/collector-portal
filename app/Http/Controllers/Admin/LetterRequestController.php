<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Unifin\Traits\LetterRequestCrud;
use Unifin\Traits\Paginate;

class LetterRequestController extends Controller
{
    use Paginate;
    use LetterRequestCrud;

    private $page = 'admin.letter-requests';

    /**
     * LetterRequestTypeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }
}
