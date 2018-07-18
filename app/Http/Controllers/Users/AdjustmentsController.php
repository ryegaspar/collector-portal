<?php

namespace App\Http\Controllers\Users;

use App\Adjustment;
use App\Rules\AdjustmentAmount;
use App\Rules\AdjustmentDate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\UserAdjustmentFilter;

class AdjustmentsController extends Controller
{
    /**
     * CollectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('users.adjustments');
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

    public function show(Request $request, Adjustment $adjustment, UserAdjustmentFilter $paginate)
    {
        $response = $adjustment->getUserAdjustments($request, $paginate);
        if ($request->wantsJson()) {
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
        } else
            return response([], 403);
    }


}
