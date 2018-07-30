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
     * CollectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.adjustments');
    }

    /**
     * return a lists of the resource in vuetable format
     *
     * @param AdminAdjustmentFilter $adminAdjustmentFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AdminAdjustmentFilter $adminAdjustmentFilter)
    {
        $response = $this->getAllAdjustments($adminAdjustmentFilter);
        if (request()->wantsJson()) {
            return response()->json($response);
        }
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

        return response([], 200);
    }

    /**
     * get all adjustments
     *
     * @param $adminAdjustmentFilter
     * @return mixed
     */
    public function getAllAdjustments($adminAdjustmentFilter)
    {
        $adjustments = Adjustment::tableFilters($adminAdjustmentFilter);

        $results = $this->paginate($adjustments);

        return $results;
    }

    /**
     * override paginate to include additional search properties
     *
     * @param $model
     * @return mixed
     */
    public function paginate($model)
    {
        $request = request();

        $perPage = $request->has('per_page') ? (int)$request->per_page : null;

        $pagination = $model->paginate($perPage)->appends([
            'sort'     => $request->sort,
            'search'   => $request->search,
            'per_page' => $request->per_page,
            'status'   => $request->created_at,
//            'paydate'  => $request->paydate,
        ]);

        return $pagination;
    }
}
