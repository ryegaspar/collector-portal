<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Lynx\LetterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\CollectorLetterRequestFilter;
use Unifin\Traits\Paginate;

class LetterRequestController extends Controller
{
    use Paginate;

    /**
     * LetterRequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * Display listing of the resource.
     *
     * @param CollectorLetterRequestFilter $collectorLetterRequestFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(CollectorLetterRequestFilter $collectorLetterRequestFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getLetterRequests($collectorLetterRequestFilter);

            return response()->json($response);
        }

        return view('collector.letter-requests');
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
        $letterRequest = new LetterRequest($data);

        $response = $request->user()->letter_requests()->save($letterRequest);

        return response($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($id)
    {
        if (request()->wantsJson()) {
            return response(LetterRequest::find($id), 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LetterRequest $letterRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(LetterRequest $letterRequest)
    {
        if (! Auth::user()->can('collectorModify', $letterRequest)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $validatedData = $this->validateData();

        $letterRequest->update($validatedData);

        return response([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LetterRequest $letterRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(LetterRequest $letterRequest)
    {
        if (! Auth::user()->can('collectorModify', $letterRequest)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $letterRequest->delete();

        return response([], 204);
    }

    /**
     * get scripts
     *
     * @param $collectorLetterRequestFilter
     * @return mixed
     */
    private function getLetterRequests($collectorLetterRequestFilter)
    {
        $letterRequests = LetterRequest::tableFilters($collectorLetterRequestFilter)->with('requestable')->with('types');

        $results = $this->paginate($letterRequests);

        return $results;
    }

    private function validateData()
    {
        request()->merge(['dbr_no' => sprintf('%010d', request()->dbr_no)]);

        return request()->validate([
            'dbr_no'         => 'required|exists:sqlsrv2.CDS.DBR,DBR_NO',
            'request_method' => 'required|numeric',
            'type'           => 'required|numeric',
            'borrower_type'  => 'required|numeric',
            'notes'          => ''
        ]);
    }
}
