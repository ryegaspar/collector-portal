<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\CHK;
use Carbon\Carbon;
use Unifin\Repositories\RawQueries;

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
        $hoursWorked = RawQueries::CollectorHoursWorked();

   //     dd($hoursWorked);
   //     dd($postToday);

        return view('admin.dashboard', compact('postToday'), compact('hoursWorked'));
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

            $data[$group]['todayCount'] = $chkToday->sum(function ($item) use ($group) {
                $today = Carbon::now();
                if ($item['Group'] == $group &&
                    Carbon::parse($item['CHK_POST_DATE_O'])->isSameDay($today))
                    return 1;
            });

            $data[$group]['todayAverage'] = $chkToday->sum(function ($item) use ($group) {
                $today = Carbon::now();
                if ($item['Group'] == $group &&
                    Carbon::parse($item['CHK_POST_DATE_O'])->isSameDay($today))
                    return $item['CHK_CHECK_AMOUNT'];
            })/(0.00000000000001 + $chkToday->sum(function ($item) use ($group) {
                $today = Carbon::now();
                if ($item['Group'] == $group &&
                    Carbon::parse($item['CHK_POST_DATE_O'])->isSameDay($today))
                    return 1;
            }));

            $data[$group]['currentMonth'] = $chkToday->sum(function ($item) use ($group) {
                $currentMonth = Carbon::now()->firstOfMonth();
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($currentMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($currentMonth)))
                    return $item['CHK_CHECK_AMOUNT'];
            });

             $data[$group]['currentMonthCount'] = $chkToday->sum(function ($item) use ($group) {
                $currentMonth = Carbon::now()->firstOfMonth();
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($currentMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($currentMonth)))
                    return 1;
            });

           $data[$group]['currentMonthAverage'] = $chkToday->sum(function ($item) use ($group) {
               $currentMonth = Carbon::now()->firstOfMonth();
               if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($currentMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($currentMonth)))
                   return $item['CHK_CHECK_AMOUNT'];
           })/(0.00000000000001 + $chkToday->sum(function ($item) use ($group) {
               $currentMonth = Carbon::now()->firstOfMonth();
               if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($currentMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($currentMonth)))
                   return 1;
           }));
// Next Month
//           $data[$group]['nextMonth'] = $chkToday->sum(function ($item) use ($group) {
//               $nextMonth = Carbon::now()->firstOfMonth()->addMonths(1);
//               if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($nextMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($nextMonth)))
//                   return $item['CHK_CHECK_AMOUNT'];
//           });
//
//            $data[$group]['nextMonthCount'] = $chkToday->sum(function ($item) use ($group) {
//               $nextMonth = Carbon::now()->firstOfMonth()->addMonths(1);
//               if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($nextMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($nextMonth)))
//                   return 1;
//           });
//
//           $data[$group]['nextMonthAverage'] = $chkToday->sum(function ($item) use ($group) {
//               $nextMonth = Carbon::now()->firstOfMonth()->addMonths(1);
//               if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($nextMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($nextMonth)))
//                   return $item['CHK_CHECK_AMOUNT'];
//           })/(0.00000000000001 + $chkToday->sum(function ($item) use ($group) {
//               $nextMonth = Carbon::now()->firstOfMonth()->addMonths(1);
//               if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameMonth($nextMonth))  && (Carbon::parse($item['CHK_POST_DATE_O'])->isSameYear($nextMonth)))
//                   return 1;
//           }));
//
            $data[$group]['next30'] = $chkToday->sum(function ($item) use ($group) {
                $startDate = Carbon::now()->addDay(-1);
                $endDate = Carbon::now()->addDay(30);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['next30Count'] = $chkToday->sum(function ($item) use ($group) {
                $startDate = Carbon::now()->addDay(-1);
                $endDate = Carbon::now()->addDay(30);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
                    return 1;

            });

            $data[$group]['next30Average'] = $chkToday->sum(function ($item) use ($group) {
                $startDate = Carbon::now()->addDay(-1);
                $endDate = Carbon::now()->addDay(30);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
                    return $item['CHK_CHECK_AMOUNT'];
            })/(0.00000000000001 + $chkToday->sum(function ($item) use ($group) {
                $startDate = Carbon::now()->addDay(-1);
                $endDate = Carbon::now()->addDay(30);
                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
                    return 1;
            }));

//            $data[$group]['next120'] = $chkToday->sum(function ($item) use ($group) {
//                $startDate = Carbon::now()->addDay(-1);
//                $endDate = Carbon::now()->addDay(120);
//                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
//                    return $item['CHK_CHECK_AMOUNT'];
//            });
//
//            $data[$group]['next120Count'] = $chkToday->sum(function ($item) use ($group) {
//                $startDate = Carbon::now()->addDay(-1);
//                $endDate = Carbon::now()->addDay(120);
//                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
//                    return 1;
//            });
//
//            $data[$group]['next120Average'] = $chkToday->sum(function ($item) use ($group) {
//                $startDate = Carbon::now()->addDay(-1);
//                $endDate = Carbon::now()->addDay(120);
//                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
//                    return $item['CHK_CHECK_AMOUNT'];
//            })/$chkToday->sum(function ($item) use ($group) {
//                $startDate = Carbon::now()->addDay(-1);
//                $endDate = Carbon::now()->addDay(120);
//                if (($item['Group'] == $group) && (Carbon::parse($item['CHK_POST_DATE_O'])->between($startDate, $endDate)))
//                    return 1;
//            });

            $data[$group]['all'] = $chkToday->sum(function ($item) use ($group) {
                if (($item['Group'] == $group))
                    return $item['CHK_CHECK_AMOUNT'];
            });

            $data[$group]['allCount'] = $chkToday->sum(function ($item) use ($group) {
                if (($item['Group'] == $group))
                    return 1;
            });

            $data[$group]['allAverage'] = $chkToday->sum(function ($item) use ($group) {
                if (($item['Group'] == $group))
                    return $item['CHK_CHECK_AMOUNT'];
            })/(0.00000000000001 + $chkToday->sum(function ($item) use ($group) {
                if (($item['Group'] == $group))
                    return 1;
            }));
                        
            return $data;
            
            
        });

        return $postToday->flatten(1);
    }
}