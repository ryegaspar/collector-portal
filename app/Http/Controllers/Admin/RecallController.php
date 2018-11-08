<?php

namespace App\Http\Controllers\Admin;

use App\Rules\RecallClassExists;
use App\Unifin\Classes\Report;
use App\Unifin\Repositories\Recalls\JcaRecall;
use App\Unifin\Repositories\Recalls\RecallInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RecallController extends Controller
{
    protected $recall;

    /**
     * RecallController constructor.
     * @param RecallInterface $recall
     */
    public function __construct(RecallInterface $recall)
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:view closure-report')->only('index');

        $this->recall = $recall;
    }

    /**
     * Display listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function index(Request $request)
    {
//        if ($request->wantsJson() || $request->has('export')) {
//            $response = $this->getSifedAccounts($request);
//
//            return response($response, 200);
//        }

        return view('admin.closures.recalls');
    }

    /**
     * Persists a new collector batch.
     *
     * @param Request $request
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function store(Request $request)
    {
        $this->validateRecall();

        if ($request->recall_method == 0) { // by date
            $this->recall
                ->makeRecallByAssignedDate($request->client, Carbon::parse($request->assigned_date)->toDateString());

            return;
        }

        $fileName = $request->file('file_input')->getClientOriginalName();

        $request->file('file_input')->storeAs('public\files\recalls', $fileName);

        $filePath = public_path('storage\\files\\recalls\\' . $fileName);

        if ($request->file_type == 0) { // generic
            $this->recall->makeRecallByFileGeneric($request->client, $filePath, $request->generic_type);
        }

        return;

        if ($request->recall_method == 0) { // by date

        } else { // by file

            $columns = collect([
                'DBR_CLI_REF_NO',
                'ADR_NAME',
                'DBR_NO',
                'DBR_NAME1',
                'DBR_ASSIGN_DATE_O',
                'DBR_CLOSE_DATE_O',
                'DBR_ASSIGN_AMT',
                'DBR_RECVD_TOT',
                'STS_DESC',
                'DBR_COM_RATE',
                'DBR_CLIENT',
                'DBR_LAST_WORKED_O',
                'DBR_STATUS',
                'count_pdc',
                'XCR_CODE'
            ]);

            $handle = fopen($filePath, "r");

            $accounts = collect();

            while (($data = fgetcsv($handle)) !== false) {
                $accounts->push($data[0]);
            }

            if ($accounts->isEmpty()) {
                throw \Illuminate\Validation\ValidationException::withMessages(['client' => 'No results found']);
            }

            $accountsStatus = collect();

            if ($request->file_type == 0) { // generic file

                $accounts->each(function ($item) use ($request, $accountsStatus) {

                    $builder = DB::connection('sqlsrv2')
                        ->table('CDS.DBR')
                        ->select(DB::raw("DBR_CLI_REF_NO, ADR.ADR_NAME, DBR_NO, DBR_NAME1, DBR_ASSIGN_DATE_O, DBR_CLOSE_DATE_O, DBR_ASSIGN_AMT, DBR_RECVD_TOT, STS.STS_DESC, DBR_COM_RATE, DBR_CLIENT, DBR_LAST_WORKED_O, DBR_STATUS, (SELECT COUNT(*) FROM CDSMSC.CHK WHERE DBR.DBR_NO = CHK.CHK_DBR_NO) as count_pdc, DBR_NO+'01XCR' as XCR_CODE"))
                        ->leftJoin('CDS.STS', 'DBR.DBR_STATUS', '=', 'STS.STS_CODE')
                        ->leftJoin('CDS.ADR', function ($join) {
                            $join->on('DBR.DBR_NO', '=', 'ADR.ADR_DBR_NO');
                            $join->on('ADR_SEQ_NO', '=', DB::raw("'R2'"));
                        })
                        ->whereRaw('DBR_CLIENT LIKE ?', ["%{$request->client}%"]);

                    if ($request->generic_type == 0) { // by client ref no
                        $account = $builder
                            ->where('DBR_CLI_REF_NO', $item)
                            ->first();

                    } else { // by original accnt num
                        $account = $builder
                            ->where('ADR_NAME', $item)
                            ->first();
                    }

                    if (! $account) {
                        $account = new \stdClass();
                        if ($request->generic_type == 0) {
                            $account->DBR_CLI_REF_NO = $item;
                            $account->ADR_NAME = 'NOT FOUND';
                        } else {
                            $account->DBR_CLI_REF_NO = 'NOT FOUND';
                            $account->ADR_NAME = $item;
                        }
                        $account->DBR_NO = 'NOT FOUND';
                        $account->DBR_NAME1 = 'NOT FOUND';
                        $account->DBR_ASSIGN_DATE_O = 'NOT FOUND';
                        $account->DBR_CLOSE_DATE_O = 'NOT FOUND';
                        $account->DBR_ASSIGN_AMT = 'NOT FOUND';
                        $account->DBR_RECVD_TOT = 'NOT FOUND';
                        $account->STS_DESC = 'NOT FOUND';
                        $account->DBR_COM_RATE = 'NOT FOUND';
                        $account->DBR_CLIENT = 'NOT FOUND';
                        $account->DBR_LAST_WORKED_O = 'NOT FOUND';
                        $account->DBR_STATUS = 'NOT FOUND';
                        $account->count_pdc = 'NOT FOUND';
                        $account->XCR_CODE = 'NOT FOUND';
                    }

                    $accountsStatus->push($account);
                });

            } else { // client specific format

                list($accountsStatus, $columns) = (new JcaRecall)->generateReport($filePath);
            }

            $accounts = collect(json_decode(json_encode($accountsStatus), true));

            (new Report)->makeSimpleXlsxFromCollection($accounts, $request->file('file_input')->getClientOriginalName(),
                $columns, $columns);

//            $filename = $request->file('file_input')->getClientOriginalName();
//
//            $csvFileName = public_path('storage\\files\\recalls\\' . $filename . '.csv');
//
//            $fp = fopen($csvFileName, 'w');
//
//            fputcsv($fp, $columns->toArray());
//
//            $accounts->each(function ($item) use ($fp) {
//                fputcsv($fp, $item);
//            });
//
//            fclose($fp);
//
//            return response()->download($csvFileName);
        }
    }

    /**
     * Validate new collector batch.
     *
     * @return mixed
     */
    protected function validateRecall()
    {
        $validator = Validator::make(request()->all(), [
                'client'        => ['required'],
                'recall_method' => ['required', 'numeric'],
            ]
        );

        $validator->sometimes('assigned_date', ['required', 'date'], function ($input) {
            return $input->recall_method == 0;
        });

        $validator->sometimes('file_type', ['required', 'numeric'], function ($input) {
            return $input->recall_method == 1;
        });

        $validator->sometimes('file_input', ['required', 'file', 'mimes:csv,txt'], function ($input) {
            return $input->recall_method == 1;
        });

        $validator->sometimes('generic_type', ['required', 'numeric'], function ($input) {
            return $input->file_type == 0 && $input->recall_method == 1;
        });

        $validator->sometimes('client', [new RecallClassExists], function ($input) {
            return $input->file_type == 1 && $input->recall_method == 1;
        });

        return $validator->validate();
    }
}
