<?php

namespace Unifin\TableFilters;

use Unifin\TableFilters\Interfaces\AdjustmentFilterInterface;

class UserAdjustmentFilter extends TableFilter implements AdjustmentFilterInterface
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