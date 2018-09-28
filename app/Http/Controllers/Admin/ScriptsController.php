<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Script;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read script')->only('index');
        $this->middleware('permission:create script')->only(['create', 'store']);
        $this->middleware('permission:update script')->only(['edit', 'update']);
        $this->middleware('permission:delete script')->only('destroy');
    }

    /**
     * Display script page.
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
     * Return specified resource.
     *
     * @param Script $script
     * @return Script
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
        return view('admin.scripts.create');
    }

    /**
     * persists a new script
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $script = $request->validate([
            'title'   => 'required',
            'content' => '',
            'status'  => ''
        ]);

        $response = Script::createScript($script);

        return response($response, 201);
    }

    /**
     * Get the specified resource to be edited.
     *
     * @param Script $script
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Script $script)
    {
        if (request()->wantsJson()) {
            return response($script, 200);
        }

        $scriptId = $script->id;

        return view('admin.scripts.edit', compact('scriptId'));
    }

    /**
     * update the given user
     *
     * @param Script $script
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Script $script)
    {
        $updatedScript = request()->validate([
            'title'   => 'required',
            'content' => '',
            'status'  => '',
        ]);

        if ($script->published_at == 'Never' && request()->status) {
            $updatedScript['published_at'] = new Carbon;
        }

        $script->update($updatedScript);

        return response([], 201);
    }

    /**
     * Delete the given script.
     *
     * @param Script $script
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Script $script)
    {
        $script->delete();

        return response([], 204);
    }

    /**
     * get scripts
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
