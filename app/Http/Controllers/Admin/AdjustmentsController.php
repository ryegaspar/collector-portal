<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Adjustment;
use Unifin\TableFilters\AdminAdjustmentFilter;
use Unifin\Traits\Paginate;
use Illuminate\Support\Facades\DB;

class AdjustmentsController extends Controller
{
    use Paginate;

    /**
     * AdjustmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read adjustment')->only('index');
        $this->middleware('permission:update adjustment')->only('update');
    }

    /**
     * display adjustments page
     *
     * @param AdminAdjustmentFilter $adminAdjustmentFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminAdjustmentFilter $adminAdjustmentFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getAdjustments($adminAdjustmentFilter);

            return response()->json($response);
        }

        return view('admin.adjustments');
    }

    /**
     * update status of the adjustment
     *
     * @param Adjustment $adjustment
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Adjustment $adjustment)
    {
        $data = request()->validate([
            'status' => 'numeric|min:1|max:2'
        ]);

        $data['reviewed_by'] = auth()->user()->id;

        $adjustment->update($data);

        if ($adjustment->status == '1') {
            $acct_no = $adjustment->dbr_no;
            $userid = strtoupper(request()->user()->tiger_user_id);
            $transferto = $adjustment->desk;
            $paymentdate = $adjustment->date;
            $paymentamount = $adjustment->amount;

            DB::connection('sqlsrv2')->update("exec [UFN].[AdjustmentApprove] ?,?,?,?,?", [$acct_no, $userid, $transferto, $paymentdate, $paymentamount]);
        
            dd($adjustment->status);
        }

        return response([], 201);
    }

    /**
     * get all adjustments
     *
     * @param $adjustmentFilter
     * @return mixed
     */
    protected function getAdjustments($adjustmentFilter)
    {
        $adjustments = Adjustment::tableFilters($adjustmentFilter)->with('reviewer');

        $results = $this->paginate($adjustments);

        return $results;
    }
}
