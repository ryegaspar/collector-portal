<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Rules\RecallClassExists;
use App\Unifin\Repositories\Recalls\Contracts\RecallInterface;
use App\Unifin\Repositories\Recalls\RecallAssignedDate;
use App\Unifin\Repositories\Recalls\RecallFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecallController extends Controller
{
    /**
     * RecallController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:view closure-report')->only('index');
    }

    /**
     * Display listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        return view('admin.closures.recalls');
    }

    /**
     * Persists a new collector batch.
     *
     * @param Request $request
     * @param RecallInterface $recall
     * @return void
     */
    public function store(Request $request, RecallInterface $recall)
    {
        $this->validateRecall();

        if ($request->recall_method == 0) { // by assigned date
            $recall->make(new RecallAssignedDate($request['client'],
                Carbon::parse($request['assigned_date'])->toDateString()));

            return;
        }

        $fileName = $request->file('file_input')->getClientOriginalName();

        $request->file('file_input')->storeAs('public\files\recalls', $fileName);

        $filePath = public_path('storage\\files\\recalls\\' . $fileName);

        if ($request->file_type) {
            $class = '\\App\\Unifin\\Repositories\\Recalls\\' . ucfirst(strtolower(request()->client)) . 'Recall';
            $recall->make(new $class($fileName, $filePath));

            return;
        }

        $recall->make(new RecallFile($request['client'], $fileName, $filePath, $request['generic_type']));

        return;

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
