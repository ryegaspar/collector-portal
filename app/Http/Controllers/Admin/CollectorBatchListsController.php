<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;
use App\Models\Lynx\CollectorBatch;
use App\Unifin\Classes\Report;
use Unifin\TableFilters\AdminCollectorFilter;
use Unifin\Traits\Paginate;

class CollectorBatchListsController extends Controller
{
    use Paginate;

    /**
     * CollectorBatchlistsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);

        $this->middleware('permission:read collector-batch')->only('index');
    }

    /**
     * Display collector batches page.
     *
     * @param $id
     * @param AdminCollectorFilter $adminCollectorFilter
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index($id, AdminCollectorFilter $adminCollectorFilter)
    {
        if (request()->wantsJson() || request()->has('import')) {

            $collectors = Collector::tableFilters($adminCollectorFilter)
                ->where('batch_id', $id)
                ->with('sub_site:id,name')
                ->with('team_leader:id,first_name,last_name');

            if (request()->wantsJson()) {
                return response($this->paginate($collectors), 200);
            }

            if (request()->import == 'excel') {

                $fileName = CollectorBatch::find($id)->name;

                $headers = collect([
                    'Name',
                    'Desk',
                    'Collect One',
                    'Portal Username',
                    'Team Leader',
                    'Sub Site',
                    'Commission',
                    'Start Date'
                ]);

                $columns = collect([
                    "full_name",
                    "desk",
                    "tiger_user_id",
                    "username",
                    ["team_leader", "full_name"],
                    ["sub_site", "name"],
                    "commission_structure_id",
                    "start_date"
                ]);

                (new Report)->makeSimpleXlsxFromModel($collectors, $fileName,$headers, $columns);

                return;
            }
        }

        return view('admin.collector-batch-lists', compact('id'));
    }
}
