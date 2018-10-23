<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClosedAccountsPdcController extends Controller
{
    /**
     * ClosedAccountsPdcController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:view closure-report')->only('index');
    }

    /**
     * Display script page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->has('export')) {
            $response = $this->getAccounts($request);

            return response($response, 200);
        }

        return view('admin.closures.closed-accounts-pdc');
    }

    private function getAccounts($request)
    {
        list($sortCol, $sortDir) = explode('|', $request->sort);

        $accounts = DB::connection('sqlsrv2')
            ->table('CDSMSC.CHK')
            ->join('CDS.DBR', 'CDSMSC.CHK.CHK_DBR_NO', '=', 'CDS.DBR.DBR_NO')
            ->select(DB::raw("DBR_NO, DBR_CLI_REF_NO, DBR_CLIENT, DBR_ASSIGN_AMT, DBR_STATUS, DBR_NAME1, DBR_CL_MISC_1, (SELECT COUNT(CDSMSC.CHK.CHK_DBR_NO) FROM CDSMSC.CHK WHERE CDS.DBR.DBR_NO = CDSMSC.CHK.CHK_DBR_NO) as chk_count"))
            ->whereRaw("(DBR_NO LIKE ? OR DBR_CLIENT LIKE ?) AND DBR_STATUS IN (?, ?, ?, ?)",
                ["%{$request->search}%", "%{$request->search}%", "DUP", "PIF", "SIF", "XCR"])
            ->havingRaw("COUNT(CDSMSC.CHK.CHK_DBR_NO) > ?", [0])
            ->groupBy('DBR_NO', 'DBR_CLI_REF_NO', 'DBR_CLIENT', 'DBR_ASSIGN_AMT', 'DBR_STATUS', 'DBR_NAME1', 'DBR_CL_MISC_1')
            ->orderBy($sortCol, $sortDir)
            ->get();

        $perPage = $request->has('per_page') ? (int)$request->per_page: 25;

        return $this->paginate($accounts, $perPage);
    }

    /**
     * Generates pagination of items in an array or collection.
     *
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    protected function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
