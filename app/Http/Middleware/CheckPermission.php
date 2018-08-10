<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $permission = explode('|', $permission);

        if ($this->checkPermission($permission)) {
            return $next($request);
        }

        return response()->view('errors.not-authorized');
    }


    protected function checkPermission($permissions)
    {
        $userAccess = $this->getPermission(auth()->user()->access_level);

        foreach ($permissions as $key => $value) {
            if ($value == $userAccess)
                return true;
        }

        return false;
    }

    /**
     * get permission of the user
     *
     * @param $id
     * @return string
     */
    protected function getPermission($id)
    {
        switch ($id) {
            case 1:
                return 'super-admin';
                break;
            case 2:
                return 'admin';
                break;
            case 3:
                return 'manager';
                break;
            default:
                return 'office support';
                break;
        }
    }
}
