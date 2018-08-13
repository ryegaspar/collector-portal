<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Script;
use Carbon\Carbon;

class ScriptPublishedController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * publish script
     *
     * @param Script $script
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Script $script)
    {
        $script->status = 1;
        $script->published_at = new Carbon;

        $script->save();

        return response([], 201);
    }
}
