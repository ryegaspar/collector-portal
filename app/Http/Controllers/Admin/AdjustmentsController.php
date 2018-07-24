<?php

namespace App\Http\Controllers\Admin;

use App\Adjustment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\AdminAdjustmentFilter;

class AdjustmentsController extends Controller
{
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
     * @param Request $request
     * @param Adjustment $adjustment
     * @param AdminAdjustmentFilter $paginate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Adjustment $adjustment, AdminAdjustmentFilter $paginate)
    {
        $response = $adjustment->getAllAdjustments($request, $paginate);
        if ($request->wantsJson()) {
            return response()->json($response);
        }
    }

    /**
     * update status of the adjustment
     *
     * @param Adjustment $adjustment
     * @param $status
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
}
