<?php

namespace App\Http\Requests\Merchant\Reports\Pos;

use Illuminate\Foundation\Http\FormRequest;

class PosSummaryReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'start-date' => 'required',
            'end-date' => 'required'
        ];
    }
}
