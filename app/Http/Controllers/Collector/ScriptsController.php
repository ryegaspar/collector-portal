<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Script;
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
        $this->middleware('collectorResetPassword');
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

        return view('collector.scripts');
    }

    /**
     * get scripts
     *
     * @param $adminScriptFilter
     * @return mixed
     */
    public function getScripts($adminScriptFilter)
    {
        $scripts = Script::tableFilters($adminScriptFilter)->whereNotNull('published_at');

        $results = $this->paginate($scripts);

        return $results;
    }
}
