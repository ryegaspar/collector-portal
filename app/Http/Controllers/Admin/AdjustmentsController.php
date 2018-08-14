<?php

namespace App\Http\Controllers\Admin;

use App\Adjustment;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\AdminAdjustmentFilter;
use Unifin\Traits\Paginate;

class AdjustmentsController extends Controller
{
    use Paginate;

    /**
     * AdjustmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'role:super-admin']);
        $this->middleware('permission:read adjustments')->only('index');
        $this->middleware('permission:update adjustments')->only('update');
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
            $response = $this->getAllAdjustments($adminAdjustmentFilter);

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

        $adjustment->update($data);

        return response([], 201);
    }

    /**
     * get all adjustments
     *
     * @param $adminAdjustmentFilter
     * @return mixed
     */
    protected function getAllAdjustments($adminAdjustmentFilter)
    {
        $adjustments = Adjustment::tableFilters($adminAdjustmentFilter);

        $results = $this->paginate($adjustments);

        return $results;
    }
}
