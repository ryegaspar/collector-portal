<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\DBR;
use App\Unifin\Classes\Report;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Unifin\TableFilters\AdminSifClosureFilter;

class SifClosuresController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:view closure-report')->only('index');
    }

    /**
     * Display script page.
     *
     * @param AdminSifClosureFilter $adminSifClosureFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(AdminSifClosureFilter $adminSifClosureFilter)
    {
        if (request()->wantsJson() || request()->has('export')) {
            $response = $this->getSifedAccounts($adminSifClosureFilter);

            return response($response, 200);
        }

        return view('admin.closures.sif-closures');
    }

    /**
     * Get SIFed accounts.
     *
     * @param $adminSifClosureFilter
     * @return LengthAwarePaginator|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function getSifedAccounts($adminSifClosureFilter)
    {
        if (request()->has('date')) {
            list($startDate, $endDate) = explode('|', request()->date);
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        $accountsWithTransactions = DBR::tableFilters($adminSifClosureFilter)
            ->select([
                'DBR_NO',
                'DBR_CLI_REF_NO',
                'DBR_STATUS',
                'DBR_RECVD_TOT',
                'DBR_NAME1',
                'DBR_CLIENT',
                'DBR_CL_MISC_1'
            ])
            ->whereHas('trs', function ($query) use ($startDate, $endDate) {
                $query->where('TRS_TRUST_CODE', '<>', 0)
                    ->whereBetween('TRS_TRX_DATE_O', [$startDate, $endDate]);
            })
            ->whereNotIn('DBR_STATUS', ['DUP', 'PIF', 'SIF', 'XCR'])
            ->with([
                'udw' => function ($query) {
                    $query->select('UDW_DBR_NO', 'UDW_SEQ', 'UDW_FLD1', 'UDW_FLD2', 'UDW_FLD3')
                        ->where('UDW_SEQ', '0ST');
                }
            ])
            ->withCount('chk' )
            ->get()->toArray();

        $sifed = collect($accountsWithTransactions)->filter(function ($item) {
            if (! empty($item['udw'])) {
                return (float)$item['DBR_RECVD_TOT'] == (float)$item['udw'][0]['UDW_FLD1'] &&
                    $item['udw'][0]['UDW_FLD3'] == 'SIF';
            }

            return false;
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
                'full_name',
                'DBR_STATUS',
                'DBR_CL_MISC_1',
                'chk_count'
            ]);

            (new Report)->makeSimpleXlsxFromCollection($collection, $fileName, $headers, $columns);

            return;
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : 25;

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
