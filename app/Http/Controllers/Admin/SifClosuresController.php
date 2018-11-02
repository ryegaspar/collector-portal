<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Unifin\Classes\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SifClosuresController extends Controller
{
    /**
     * SifClosuresController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:view closure-report')->only('index');
    }

    /**
     * Display listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->has('export')) {
            $response = $this->getSifedAccounts($request);

            return response($response, 200);
        }

        return view('admin.closures.sif-closures');
    }

    /**
     * Get SIFed accounts.
     *
     * @param $request
     * @return LengthAwarePaginator|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function getSifedAccounts($request)
    {
        list($startDate, $endDate) = explode('|', $request->date);
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        list($sortCol, $sortDir) = explode('|', $request->sort);

        $accountsWithTransactions = DB::connection('sqlsrv2')
            ->table('CDS.DBR')
            ->leftJoin('UFN.UDW_0ST', 'CDS.DBR.DBR_NO', '=', 'UFN.UDW_0ST.UDW_DBR_NO')
            ->select(DB::raw('DBR_NO, DBR_CLI_REF_NO, DBR_STATUS, DBR_RECVD_TOT, DBR_NAME1, DBR_CLIENT, DBR_CL_MISC_1, UDW_FLD1, UDW_FLD2, UDW_FLD3, (SELECT COUNT(*) FROM CDSMSC.CHK WHERE CDS.DBR.DBR_NO = CDSMSC.CHK.CHK_DBR_NO) as [chk_count]'))
            ->whereRaw("(DBR_NO LIKE ? OR DBR_CLIENT LIKE ?) AND EXISTS (SELECT * FROM CDS.TRS WHERE CDS.DBR.DBR_NO = CDS.TRS.TRS_DBR_NO AND TRS_TRUST_CODE <> ? AND TRS_TRX_DATE_O BETWEEN ? and ?) AND DBR_STATUS NOT IN (?, ?, ?, ?)",
                ["%{$request->search}%", "%{$request->search}%", 0, $startDate, $endDate, "DUP", "PIF", "SIF", "XCR"])
            ->orderBy($sortCol, $sortDir)
            ->get();

        $accountsWithTransactions = json_decode(json_encode($accountsWithTransactions), true);

        $sifed = collect($accountsWithTransactions)->filter(function ($item) {
            $matches = [];
            $udwFld1 = 0;

            preg_match('/[^\$+]?(\d+[,]?\d*\.?\d+?)$/', $item['UDW_FLD1'], $matches);
            if ($matches)
                $udwFld1 = str_replace(',', '', $matches[0]);

            return (float)$item['DBR_RECVD_TOT'] == (float)$udwFld1 && $item['UDW_FLD3'] == 'SIF';
        });

        return $this->present($sifed);
    }

    /**
     * Present the collection.
     *
     * @param $collection
     * @return LengthAwarePaginator|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function present($collection)
    {
        if (request()->has('export') && request()->export == 'xlsx') {
            $fileName = 'SIF_Closures';

            $headers = collect([
                'DBR #',
                'DBR Client Ref #',
                'Name',
                'Status',
                'Client',
                'PDC Count'
            ]);

            $columns = collect([
                'DBR_NO',
                'DBR_CLI_REF_NO',
                'DBR_NAME1',
                'DBR_STATUS',
                'DBR_CL_MISC_1',
                'chk_count'
            ]);

            (new Report)->makeSimpleXlsxFromCollection($collection, $fileName, $headers, $columns);

            return;
        }

        $perPage = request()->has('per_page') ? (int)request()->per_page : 25;

        return $this->paginate($collection, $perPage);
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
