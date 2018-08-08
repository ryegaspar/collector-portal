<?php

namespace App\Http\Controllers\Admin;

use App\Script;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\AdminScriptFilter;
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
     * @param AdminScriptFilter $adminScriptFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminScriptFilter $adminScriptFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getScripts($adminScriptFilter);
            return response()->json($response);

        }
        return view('admin.scripts');
    }

    /**
     * return a lists of the resource in vuetable format
     *
     * @param AdminScriptFilter $adminScriptFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Script $script)
    {
        return $script;
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {
        $script = request()->validate([
            'title'   => 'required',
            'content' => '',
            'status'  => ''
        ]);

        $response = Auth::user()->createScript($script);

        return response($response, 201);
    }

    /**
     * get users
     *
     * @param $adminScriptFilter
     * @return mixed
     */
    public function getScripts($adminScriptFilter)
    {
        $scripts = Script::tableFilters($adminScriptFilter);

        $results = $this->paginate($scripts);

        return $results;
    }
}
