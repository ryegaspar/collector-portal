<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Script;
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
        $this->middleware(['auth:admin', 'activeUser', 'role:super-admin']);
        $this->middleware('permission:read scripts')->only('index');
        $this->middleware('permission:create scripts')->only(['create', 'store']);
        $this->middleware('permission:update scripts')->only(['edit', 'update']);
        $this->middleware('permission:delete scripts')->only('destroy');
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
            'title'        => 'required',
            'content'      => '',
            'status'       => '',
        ]);

        if ($script->status) {
            unset($updatedScript['status']);
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
