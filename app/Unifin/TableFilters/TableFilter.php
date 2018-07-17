<?php

namespace Unifin\TableFilters;

use Illuminate\Http\Request;

abstract class TableFilter
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * the eloquent builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * searches to operate on
     *
     * @var array
     */
    protected $searches = [];

    /**
     * default sort
     *
     * @var string
     */
    protected $defaultSort = '';

    /**
     * create a new Tabulation instance
     *
     * Tabulation constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * perform search on builder if any
     *
     * @return $this
     */
    protected function search()
    {
        $this->builder->where(function ($q) {
            $numToSearch = 0;

            foreach ($this->searches as $search) {
                if ($numToSearch == 0) {
                    $q = $q->where($search, 'like', "%{$this->request->search}%");
                    $numToSearch++;
                } else {
                    $q = $q->orWhere($search, 'like', "%{$this->request->search}%");
                    $numToSearch++;
                }
            }
        });

        return $this;
    }

    /**
     * perform sort
     *
     * @return $this
     */
    protected function sort()
    {
        if ($this->request->filled('sort')) {
            $sorts = explode(',', $this->request->sort);
            foreach ($sorts as $sort) {
                list($sortCol, $sortDir) = explode('|', $sort);
                $this->builder = $this->builder->orderBy($sortCol, $sortDir);
            }
        } else {
            $this->builder = $this->builder->orderBy($this->defaultSort, 'asc');
        }

        return $this;
    }

    /**
     * apply tabulation
     *
     * @param $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        $this->search()->sort();

        return $this->builder;
    }
}