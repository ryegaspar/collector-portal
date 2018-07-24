<?php

namespace Unifin\TableFilters;

use Unifin\TableFilters\Interfaces\AdjustmentFilterInterface;

class AdminAdjustmentFilter extends TableFilter implements AdjustmentFilterInterface
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