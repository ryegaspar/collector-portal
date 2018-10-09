<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lynx\LetterRequestType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\AdminLetterRequestTypeFilter;
use Unifin\Traits\Paginate;

class LetterRequestTypeController extends Controller
{
    use Paginate;

    /**
     * LetterRequestTypeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read letter-request-type')->only('index');
        $this->middleware('permission:create letter-request-type')->only('store');
        $this->middleware('permission:update letter-request-type')->only(['edit', 'update']);
    }

    /**
     * Display listing of the resource.
     *
     * @param AdminLetterRequestTypeFilter $adminLetterRequestTypeFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminLetterRequestTypeFilter $adminLetterRequestTypeFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->paginate(LetterRequestType::tableFilters($adminLetterRequestTypeFilter));

            return response($response, 200);
        }

        return view('admin.letter-request-types');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $letterRequestType = $request->validate([
            'name'        => 'required',
            'description' => '',
        ]);

        $response = LetterRequestType::create($letterRequestType);

        return response($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LetterRequestType $letterRequestType
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(LetterRequestType $letterRequestType)
    {
        if (request()->wantsJson()) {
            return response($letterRequestType, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LetterRequestType $letterRequestType
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(LetterRequestType $letterRequestType)
    {
        $data = request()->validate([
            'name'        => 'required',
            'description' => '',
        ]);

        $letterRequestType->update($data);

        return response([], 201);
    }
}
