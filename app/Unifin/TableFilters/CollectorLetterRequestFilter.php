<?php

namespace Unifin\TableFilters;

class CollectorLetterRequestFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['creator_name', 'dbr_no'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'updated_at';
}