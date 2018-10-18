<?php

namespace Unifin\TableFilters;

class AdminSifClosureFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['DBR_NO', 'DBR_CLIENT'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'chk_count';
}