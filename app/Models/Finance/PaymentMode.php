<?php

namespace App\Models\Finance;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property mixed $id
 * @property mixed $display_name
 * @property mixed $name
 */
class PaymentMode extends BaseModel
{
    use HasFactory;
}
