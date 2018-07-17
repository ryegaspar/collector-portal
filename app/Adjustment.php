<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Adjustment extends Model
{
    protected $fillable = ['desk', 'commission', 'dbr_no', 'amount', 'date'];

    public static function appendAdjustment()
    {
        $adj = new Adjustment;

        $adj->desk = Auth::user()->USR_DEF_MOT_DESK;
        $adj->dbr_no = request()->dbr_no;
        $adj->amount = request()->amount;
        $adj->date = request()->date;
//        $adjustment->merge(array('desk' => Auth::user()->USR_DEF_MOT_DESK));

        $date = Carbon::parse(request()->date)->toDateString();
        $debterPayment = DebterPayment::where('PAY_DBR_NO', '=', request()->dbr_no)
                ->whereDate('PAY_DATE_O', '=', $date)
                ->where('PAY_AMT', '=', request()->amount)
                ->where('PAY_STATUS', '=', 'T')
                ->first();

        $adj->commision = $debterPayment->PAY_COMM;

        return $adj->create();
    }
}
