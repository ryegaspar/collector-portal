<?php

namespace Unifin\TableFilters;

class AdminSiteFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['id', 'name'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'created_at';
}