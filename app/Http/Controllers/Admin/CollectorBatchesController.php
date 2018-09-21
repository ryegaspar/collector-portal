<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\CollectorBatch;
use App\Models\Lynx\Subsite;
use App\Unifin\Classes\NewCollectorFromBatch;
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
        $this->middleware('permission:delete collector-batch')->only('destroy');
    }

    /**
     * Display collector batches page.
     *
     * @param AdminCollectorBatchFilter $adminCollectorBatchFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminCollectorBatchFilter $adminCollectorBatchFilter)
    {
        if (request()->wantsJson()) {
            $collectorBatches = CollectorBatch::tableFilters($adminCollectorBatchFilter)
                ->with('sub_site:id,name')
                ->withCount('collectors');

            $result = $this->paginate($collectorBatches);

            return response($result, 200);
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
        $validatedData['start_date'] = Carbon::parse($validatedData['start_date']);

        $collectorBatch = CollectorBatch::create($validatedData);

        (new NewCollectorFromBatch($request->file('csv_file'), $validatedData, $collectorBatch))->makeCollectors();

        return response([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CollectorBatch $collectorBatch
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(CollectorBatch $collectorBatch)
    {
        $collectorBatch->delete();

        return response([], 204);
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
}
