<?php

namespace Unifin\TableFilters;

class AdminUserFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['first_name', 'last_name' , 'username'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'created_at';
}