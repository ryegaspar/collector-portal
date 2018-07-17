<?php

namespace App\Http\Controllers\Users;

use App\Adjustment;
use App\DebterPayment;
use App\Rules\AdjustmentAmount;
use App\Rules\AdjustmentDate;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(Request $request)
    {
        $adjustment = $request->validate([
            'dbr_no' => 'required|exists:sqlsrv2.CDS.DBR,DBR_NO',
            'amount' => ['required', new AdjustmentAmount],
            'date'   => ['required', new AdjustmentDate],
        ]);

        $adjustment['date'] = Carbon::parse(request()->date)->format('Y-m-d');

        $adjustment['commission'] = DebterPayment::getFirstPaymentCommission($request->dbr_no, $request->amount, $request->date)->PAY_COMM;

        $adjustment['desk'] = Auth::user()->USR_DEF_MOT_DESK;

        $response = Adjustment::create($adjustment);

        if (request()->wantsJson()) {
            return response($response, 201);
        }
    }
}
