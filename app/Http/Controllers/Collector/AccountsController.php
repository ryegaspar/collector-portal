<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Tiger\DBR;
use Unifin\TableFilters\CollectorAccountFilter;
use Unifin\Traits\Paginate;

class AccountsController extends Controller
{
    use Paginate;

    /**
     * CollectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * show accounts page
     * @param CollectorAccountFilter $collectorAccountFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(CollectorAccountFilter $collectorAccountFilter)
    {
        if (request()->wantsJson()) {
            $dbr = $this->getUserAccounts($collectorAccountFilter);

            return $dbr;
        }

        return view('collector.accounts');
    }

    /**
     * fetch all relevant accounts for the user
     *
     * @param CollectorAccountFilter $userAccountFilter
     * @return mixed
     */
    public function getUserAccounts($userAccountFilter)
    {
        $dbr = DBR::userAccounts()->tableFilters($userAccountFilter);

        $results = $this->paginate($dbr);

        return $results;
    }
}
