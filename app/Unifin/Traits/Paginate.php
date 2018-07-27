<?php

namespace Unifin\Traits;

trait Paginate
{
    /**
     * paginate for vuetable view
     *
     * @param $model
     * @return mixed
     */
    public function paginate($model)
    {
        $perPage = request()->has('per_page') ? (int)request()->per_page : null;

        $result = $model->paginate($perPage);
        $result->appends([
            'sort'     => request()->sort,
            'search'   => request()->search,
            'per_page' => request()->per_page,
        ]);

        return $result;
    }
}