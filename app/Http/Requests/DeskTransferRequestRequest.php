<?php

namespace App\Http\Requests;

use App\Models\Tiger\DBR;
use App\Rules\DbrNotForReviewInDeskTransferRequest;
use App\Rules\DbrNotInHouse;
use Illuminate\Foundation\Http\FormRequest;

class DeskTransferRequestRequest extends FormRequest
{
    protected $id;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dbr_no'         => [
                'required',
                'exists:sqlsrv2.CDS.DBR,DBR_NO',
                new DbrNotInHouse,
                new DbrNotForReviewInDeskTransferRequest($this->id)
            ],
            'request_reason' => ['required', 'numeric'],
            'notes'          => '',
            'desk'           => ['required', 'different:desk_from'],
            'desk_from'      => '',
            'creator_name'   => ''
        ];
    }

    protected function prepareForValidation()
    {
        $this->id = $this->desk_transfer_request ? $this->desk_transfer_request->id : null;

        $this->merge(['dbr_no' => sprintf('%010d', $this->dbr_no)]);
        $this->merge(['desk' => sprintf('%03d', auth()->user()->desk ? auth()->user()->desk : $this->desk)]);
        $this->merge(['creator_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name]);
        $this->merge(['desk_from' => DBR::find($this->dbr_no) ? DBR::find($this->dbr_no)->DBR_DESK : '']);
    }
}
