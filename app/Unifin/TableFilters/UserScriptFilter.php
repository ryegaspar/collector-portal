<?php

namespace Unifin\TableFilters;

use Carbon\Carbon;

class UserScriptFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['id', 'title'];

    /**
     * columns on a related table to search for
     *
     * @var array
     */
    protected $searchesAs = ['user' => ['first_name', 'last_name']];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'published_at';
}