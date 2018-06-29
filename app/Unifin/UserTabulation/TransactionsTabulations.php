<?php

namespace Unifin\UserTabulation;

class TransactionsTabulations extends Tabulation
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['PAY_DBR_NO'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'PAY_DBR_NO';
}