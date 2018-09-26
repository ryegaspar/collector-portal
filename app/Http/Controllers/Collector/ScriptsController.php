<?php

namespace App\Http\Controllers\Collector;

use App\Script;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\CollectorScriptFilter;
use Unifin\Traits\Paginate;

class ScriptsController extends Controller
{
    use Paginate;

    /**
     * create new instance of ScriptsController
     */
    public function __construct()
    {

        $this->middleware('auth');
    }

    /**
     * show scripts page
     *
     * @param CollectorScriptFilter $scriptFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(CollectorScriptFilter $scriptFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getScripts($scriptFilter);

            return response()->json($response);
        }

        return view('users.scripts');
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
     * get scripts
     *
     * @param $adminScriptFilter
     * @return mixed
     */
    public function getScripts($adminScriptFilter)
    {
        $scripts = Script::tableFilters($adminScriptFilter)->where('status', 1);

        $results = $this->paginate($scripts);

        return $results;
    }
}
