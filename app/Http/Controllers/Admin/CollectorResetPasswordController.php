<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;

class CollectorResetPasswordController extends Controller
{
    /**
     * CollectorResetPassword constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);

        $this->middleware('permission:update collector')->only('update');
    }

    /**
     * Reset collector password
     *
     * @param Collector $collector
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Collector $collector)
    {
        $collector->change_pass_at = null;
        $collector->password = bcrypt("Password1");
        $collector->save();

        return response([], 201);
    }
}
