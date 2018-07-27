<?php

namespace Unifin\TableFilters;

class AdminAdjustmentFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['desk', 'name'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'date';
}