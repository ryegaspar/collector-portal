<?php

namespace Unifin\Traits;

use App\Models\Lynx\DeskTransferRequest;
use Illuminate\Http\Request;
use App\Rules\LetterRequestType;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\CollectorDeskTransferRequestFilter;
use App\Models\Tiger\DBR;

trait DeskTransferRequestCrud
{
    /**
     * Display listing of the resource.
     *
     * @param CollectorDeskTransferRequestFilter $collectorDeskTransferRequestFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(CollectorDeskTransferRequestFilter $collectorDeskTransferRequestFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getDeskTransferRequests($collectorDeskTransferRequestFilter);

            return response()->json($response);
        }

        return view($this->page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateData();

        $user = $request->user();

        //temporary fix, filter eager loaded in polymorphic relations not working in sqlsrv
        //TODO: remove if updated or fixed.
        $data['creator_name'] = $user->first_name . ' ' . $user->last_name;

        $debterRecord = DBR::findOrFail($data['dbr_no']);
        $data['desk_from'] = $debterRecord->DBR_DESK;
        $data['desk'] = Auth::user()->desk ? Auth::user()->desk : $data['desk'];

        $deskTransferRequest = new DeskTransferRequest($data);

        $response = $request->user()->desk_transfer_requests()->save($deskTransferRequest);

        return response($response, 200);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param $id
//     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
//     */
//    public function edit($id)
//    {
//        if (request()->wantsJson()) {
//            return response(DeskTransferRequest::find($id), 200);
//        }
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param DeskTransfer $deskTransferRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(DeskTransferRequest $deskTransferRequest)
    {
        if (! Auth::user()->can('modify', $deskTransferRequest)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $validatedData = $this->validateData();

        $deskTransferRequest->update($validatedData);

        return response([], 201);
    }

    /**
     * Get list of desk transfer requests
     *
     * @param $collectorDeskTransferRequestFilter
     * @return mixed
     */
    private function getDeskTransferRequests($collectorDeskTransferRequestFilter)
    {
        $deskTransferRequests = DeskTransferRequest::tableFilters($collectorDeskTransferRequestFilter)
            ->with('requestable:id,tiger_user_id,last_name,first_name')
             ->with('checked_by:id,last_name,first_name');

        $results = $this->paginate($deskTransferRequests);

        return $results;
    }

    /**
     * Validate desk transfer request.
     *
     * @return mixed
     */
    private function validateData()
    {
        request()->merge(['dbr_no' => sprintf('%010d', request()->dbr_no)]);
        request()->merge(['desk' => sprintf('%03d', request()->desk)]);

        return request()->validate([
            'dbr_no'         => 'required|exists:sqlsrv2.CDS.DBR,DBR_NO',
            'request_reason' => ['required', 'numeric'],
            'notes'          => '',
            'desk'           => ''
        ]);
    }
}