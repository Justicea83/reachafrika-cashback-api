<?php

namespace App\Exports;

use App\Dtos\Merchant\Finance\TransactionDto;
use App\Models\Finance\Transaction;
use App\Models\User;
use App\Utils\Merchant\TransactionsFilterOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    use Exportable;
    private TransactionsFilterOptions $filterOptions;
    private User $user;

    function __construct(TransactionsFilterOptions $filterOptions, User $user)
    {
        $this->filterOptions = $filterOptions;
        $this->user = $user;
    }

    public function collection(): Collection
    {
        $filterOptions = $this->filterOptions;
        $user = $this->user;

        return Transaction::query()
            ->where('merchant_id', $user->merchant_id)
            ->where('pos_id', $user->pos->id)
            ->when($filterOptions->amountStart, function (Builder $query) use ($filterOptions) {
                $query->where('amount', '>=', $filterOptions->amountStart);
            })
            ->when($filterOptions->amountEnd, function (Builder $query) use ($filterOptions) {
                $query->where('amount', '<=', $filterOptions->amountEnd);
            })
            ->when($filterOptions->startDate, function (Builder $query) use ($filterOptions) {
                $query->where('created_at', '>=', Carbon::parse($filterOptions->startDate)->unix());
            })
            ->when($filterOptions->endDate, function (Builder $query) use ($filterOptions) {
                $checkDate = Carbon::parse($filterOptions->endDate);
                if ($filterOptions->startDate == $filterOptions->endDate)
                    $checkDate->addHours(24);
                $query->where('created_at', '<=', $checkDate->unix());
            })
            ->when($filterOptions->statuses, function (Builder $query) use ($filterOptions) {
                $statuses = explode(',', $filterOptions->statuses);
                $query->whereIn('status', $statuses);
            })
            ->get()
            ->map(fn(Transaction $transaction) => TransactionDto::map($transaction));
    }

    public function headings(): array
    {
        return [
           '#',
           'Amount',
           'Formatted Amount',
           'Transaction',
           'Tax Percentage',
           'Discount',
           'Balance Before',
           'Balance After',
           'Currency',
           'Currency Symbol',
           'Status',
           'Reference',
           'Created At',
           'Branch Name',
           'Pos',
           'Cashier',
           'Service Charge',
           'Phone',
            'Payment Mode',
           'Date'
        ];
    }
}
