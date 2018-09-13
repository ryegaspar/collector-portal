<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;
use App\Models\Lynx\CollectorBatch;
use App\Models\Lynx\Subsite;
use App\Unifin\Classes\NewCollector;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Unifin\TableFilters\AdminCollectorBatchFilter;
use Unifin\Traits\Paginate;

class CollectorBatchesController extends Controller
{
    use Paginate;

    /**
     * CollectorController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);

        $this->middleware('permission:read collector-batch')->only('index');
        $this->middleware('permission:create collector-batch')->only('store');
//        $this->middleware('permission:delete batch-collector')->only(['edit', 'update']);
    }

    /**
     * Display adjustments page
     *
     * @param AdminCollectorBatchFilter $adminCollectorBatchFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminCollectorBatchFilter $adminCollectorBatchFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getCollectorBatches($adminCollectorBatchFilter);

            return response($response, 200);
        }

        return view('admin.collector-batches');
    }

    /**
     * Persists a new collector batch.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateNewCollectorBatch();
        $startDate = $validatedData['start_date'] = Carbon::parse($validatedData['start_date']);

        $collectorBatch = CollectorBatch::create($validatedData);

        $request->file('csv_file')
            ->storeAs('public\files', $request->name . ".csv");

        $filename = $request->name . ".csv";
        $filePath = public_path('storage\\files\\' . $filename);

        $fileHandle = fopen($filePath, "r");

        $row = 0;
        while (($data = fgetcsv($fileHandle)) !== false) {
            $row++;
            if ($row == 1) continue;

            $lastName = $data[0];
            $firstName = $data[1];

            $ids = (new NewCollector($validatedData['sub_site_id'], $firstName, $lastName))
                ->generateId();

            $tempDate = Carbon::parse($validatedData['start_date'])->day(1);
            $fifteenth = Carbon::parse($validatedData['start_date'])->day(15);
            $validatedData['start_full_month_date'] = $startDate <= $fifteenth ? $tempDate : $tempDate->addMonth();

            $newCollector = new Collector([
                'desk'                    => $ids[0],
                'tiger_user_id'           => $ids[1],
                'username'                => $ids[2],
                'last_name'               => $lastName,
                'first_name'              => $firstName,
                'sub_site_id'             => $validatedData['sub_site_id'],
                'team_leader_id'          => $validatedData['team_leader_id'],
                'commission_structure_id' => $validatedData['commission_structure_id'],
                'start_date'              => $validatedData['start_date'],
                'start_full_month_date'   => $validatedData['start_full_month_date']
            ]);

            $collectorBatch->collectors()->save($newCollector);
        }

        return response([], 200);
    }

    /**
     * Validate new collector batch.
     *
     * @return mixed
     */
    protected function validateNewCollectorBatch()
    {
        $validator = Validator::make(request()->all(), [
                'csv_file'                => ['required', 'file', 'mimes:csv,txt'],
                'name'                    => ['required'],
                'sub_site_id'             => ['required'],
                'start_date'              => ['required'],
                'commission_structure_id' => ['required']
            ]
        );

        $validator->sometimes('team_leader_id', ['required', 'numeric'], function ($input) {
            if (empty($input->sub_site_id)) {
                return false;
            }
            $site = Subsite::find($input->sub_site_id);

            return ! ! $site->has_team_leaders;
        });

        return $validator->validate();
    }

    /**
     * Get collectors.
     *
     * @param $adminCollectorBatches
     * @return mixed
     */
    protected function getCollectorBatches($adminCollectorBatches)
    {
        $collectorBatches = CollectorBatch::tableFilters($adminCollectorBatches)->with('sub_site:id,name')->withCount('collectors');

        $results = $this->paginate($collectorBatches);

        return $results;
    }
}
