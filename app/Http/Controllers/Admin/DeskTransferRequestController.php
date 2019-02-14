<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Unifin\Traits\DeskTransferRequestCrud;
use Unifin\Traits\Paginate;

class DeskTransferRequestController extends Controller
{
    use Paginate;
    use DeskTransferRequestCrud;

    private $page = 'admin.desk-transfer-requests';

    /**
     * DeskTransferRequestTypeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param DeskTransferRequest $deskTransferRequest
//     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
//     * @throws \Exception
//     */
//    public function destroy(DeskTransferRequest $deskTransferRequest)
//    {
//        $deskTransferRequest->delete();
//
//        return response([], 204);
//    }
}
