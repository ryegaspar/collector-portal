<?php

namespace Unifin\Traits;

use App\Http\Requests\DeskTransferRequestRequest;
use App\Models\Lynx\DeskTransferRequest;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\CollectorDeskTransferRequestFilter;

trait DeskTransferRequestCrud
{

    public function __construct()
    {
        $this->middleware('can:modify, App\Models\Lynx\DeskTransferRequest')->only('update');
    }

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
     * @param DeskTransferRequestRequest $deskTransferRequestRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(DeskTransferRequestRequest $deskTransferRequestRequest)
    {
        $deskTransferRequest = new DeskTransferRequest($deskTransferRequestRequest->validated());

        $response = auth()->user()->desk_transfer_requests()->save($deskTransferRequest);

        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DeskTransferRequestRequest $deskTransferRequestRequest
     * @param DeskTransferRequest $deskTransferRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(DeskTransferRequestRequest $deskTransferRequestRequest, DeskTransferRequest $deskTransferRequest)
    {
        $deskTransferRequest->update($deskTransferRequestRequest->validated());

        return response([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeskTransferRequest $deskTransferRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(DeskTransferRequest $deskTransferRequest)
    {
        if (! Auth::user()->can('modify', $deskTransferRequest)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $deskTransferRequest->delete();

        return response([], 204);
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
}