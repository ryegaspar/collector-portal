<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\CHK;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * display dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $postToday = $this->getSummary();

        return view('admin.dashboard', compact('postToday'));
    }

    /**
     * Get Todays total setup summary.
     *
     * @return mixed
     */
    private function getSummary()
    {
        $chkToday = CHK::setupToday()
            ->select(['CHK_CHECK_AMOUNT', 'EntryDate', 'CHK_USERID', 'CHK_POST_DATE_O'])
            ->with(['usr:USR_CODE,USR_GROUP', 'usr.ugp:UGP_CODE,UGP_DESC'])
            ->get()
            ->toArray();

        $chkToday = collect($chkToday)->map(function ($item) {
            $data['CHK_CHECK_AMOUNT'] = $item['CHK_CHECK_AMOUNT'];
            $data['EntryDate'] = $item['EntryDate'];
            $data['CHK_POST_DATE_O'] = Carbon::parse($item['CHK_POST_DATE_O'])->toDateString();
            $data['Group'] = $item['usr']['ugp']['UGP_DESC'];
            return $data;
        });

        $groups = $chkToday->unique('Group')->pluck('Group')->values();

        $postToday = $groups->map(function ($group) use ($chkToday) {

            $data[$group]['name'] = $group;

            $data[$group]['today'] = $chkToday->sum(function ($item) use ($group) {
                $today = Carbon::now();
                if ($item['Group'] == $group &&
                    Carbon::parse($item['CHK_POST_DATE_O'])->isSameDay($today))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['currentMonth'] = $chkToday->sum(function ($item) use ($group) {
                $currentMonth = Carbon::now()->firstOfMonth();
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($currentMonth)))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['nextMonth'] = $chkToday->sum(function ($item) use ($group) {
                $nextMonth = Carbon::now()->firstOfMonth()->addMonths(1);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($nextMonth)))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['next30'] = $chkToday->sum(function ($item) use ($group) {
                $startDate = Carbon::now();
                $endDate = Carbon::now()->addDay(30);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['next120'] = $chkToday->sum(function ($item) use ($group) {
                $startDate = Carbon::now();
                $endDate = Carbon::now()->addDay(120);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['all'] = $chkToday->sum(function ($item) use ($group) {
                if (($item['Group'] == $group))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            return $data;
        });

        return $postToday->flatten(1);
    }
}
