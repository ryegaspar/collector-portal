<?php

namespace Unifin\TableFilters;

class UserAccountFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['DBR_NAME1', 'DBR_NO'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'DBR_NO';
}