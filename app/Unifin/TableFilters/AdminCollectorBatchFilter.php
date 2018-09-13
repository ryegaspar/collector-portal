<?php

namespace Unifin\TableFilters;

class AdminCollectorBatchFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['name'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'created_at';

}