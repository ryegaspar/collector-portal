<?php

namespace Unifin\TableFilters;

class UserAdjustmentFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['dbr_no'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'date';
}