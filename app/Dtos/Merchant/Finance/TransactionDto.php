<?php

namespace App\Dtos\Merchant\Finance;

use App\Dtos\BaseDto;
use App\Models\Finance\Transaction;
use Carbon\Carbon;

class TransactionDto extends BaseDto
{

    public int $id;
    public float $amount;
    public string $formatted_amount;
    public string $transaction;
    public float $tax_percentage;
    public float $given_discount;
    public float $balance_before;
    public float $balance_after;
    public string $currency;
    public string $currency_symbol;
    public string $status;
    public string $reference;
    public string $created_at;
    public string $branch_name;
    public string $pos;
    public string $cashier;
    public string $phone;

    /**
     * @param Transaction $model
     */
    public function mapFromModel($model): TransactionDto
    {
        $instance = self::instance();
        $instance->id = $model->id;
        $instance->amount = $model->amount;
        $instance->formatted_amount = sprintf("%s %s", $model->currency_symbol, number_format($model->amount, 2));
        $instance->transaction = $model->transaction;
        $instance->tax_percentage = $model->tax_percentage;
        $instance->given_discount = $model->given_discount;
        $instance->balance_before = $model->balance_before;
        $instance->balance_after = $model->balance_after;
        $instance->currency = $model->currency;
        $instance->currency_symbol = $model->currency_symbol;
        $instance->status = $model->status;
        $instance->phone = $model->user_phone;
        $instance->reference = $model->reference;
        $instance->created_at = Carbon::parse($model->created_at)->toDateTimeString();
        $instance->branch_name = $model->branch->name;
        $instance->pos = $model->pos->name;
        $instance->cashier = sprintf("%s %s", $model->cashier->first_name, $model->cashier->last_name);
        return $instance;
    }

    private static function instance(): TransactionDto
    {
        return new TransactionDto();
    }

    public static function map(Transaction $transaction): TransactionDto
    {
        $instance = self::instance();
        return $instance->mapFromModel($transaction);
    }
}
