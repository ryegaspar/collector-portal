<?php

namespace App\Http\Controllers\Admin;

use App\Adjustment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
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

//    /**
//     * override paginate to include additional search properties
//     *
//     * @param $model
//     * @return mixed
//     */
//    protected function paginate($model)
//    {
//        $request = request();
//
//        $perPage = $request->has('per_page') ? (int)$request->per_page : null;
//
//        $pagination = $model->paginate($perPage)->appends([
//            'sort'     => $request->sort,
//            'search'   => $request->search,
//            'per_page' => $request->per_page,
//            'status'   => $request->created_at,
////            'paydate'  => $request->paydate,
//        ]);
//
//        return $pagination;
//    }
}
