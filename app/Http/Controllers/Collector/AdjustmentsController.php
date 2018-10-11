<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Adjustment;
use App\Rules\AdjustmentAmount;
use App\Rules\AdjustmentDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\CollectorAdjustmentFilter;
use Unifin\Traits\Paginate;

class AdjustmentsController extends Controller
{
    use Paginate;

    /**
     * CollectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * display adjustments page
     *
     * @param CollectorAdjustmentFilter $collectorAdjustmentFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CollectorAdjustmentFilter $collectorAdjustmentFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getUserAdjustments($collectorAdjustmentFilter);

            return response()->json($response);
        }

        return view('collector.adjustments');
    }

    /**
     * persists a new adjustment
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $request->merge(['dbr_no' => sprintf('%010d', $request->dbr_no)]);

        $adjustment = $request->validate([
            'dbr_no' => 'required|exists:sqlsrv2.CDS.DBR,DBR_NO',
            'amount' => ['required', new AdjustmentAmount],
            'date'   => ['required', new AdjustmentDate],
        ]);

        $response = Adjustment::addCollectorAdjustment($adjustment);

        if (request()->wantsJson()) {
            return response($response, 201);
        }
    }

    /**
     * return a lists of the resource in vuetable format
     *
     * @param CollectorAdjustmentFilter $userAdjustmentFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(CollectorAdjustmentFilter $userAdjustmentFilter)
    {
        $response = $this->getUserAdjustments($userAdjustmentFilter);
        if (request()->wantsJson()) {
            return response()->json($response);
        }
    }

    /**
     * delete the given adjustment data
     *
     * @param Adjustment $adjustment
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Adjustment $adjustment)
    {
        if (Auth::user()->can('delete', $adjustment)) {
            $adjustment->delete();

            return response([], 204);
        } else {
            return response([], 403);
        }
    }

    /**
     * fetch all relevant adjustments of the user
     *
     * @param $userAdjustmentFilter
     * @return mixed
     */
    public function getUserAdjustments($userAdjustmentFilter)
    {
        $adjustments = Adjustment::userAdjustments()->tableFilters($userAdjustmentFilter);

        $results = $this->paginate($adjustments);

        return $results;
    }
}
