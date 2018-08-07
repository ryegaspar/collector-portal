<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\Traits\Paginate;

class ScriptsController extends Controller
{
    use Paginate;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'can:access-admin']);
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.scripts');
    }

    /**
     * display script create page
     *
     */
    public function create()
    {
        return view('admin.scripts.create_edit');
    }

    /**
     * persists a new script
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $user = $request->validate([
            'title'     => ['required'],
        ]);

        return response([], 201);
    }
}
