<?php

namespace App\Http\Models\Financial;

use Illuminate\Database\Eloquent\Model;

class FinancialSubmissionDetail extends Model
{
    protected $fillable = [
        'financial_submission_id',
        'value',
        'qty',
        'price',
        'total_price',
        'unit',
        'note'
    ];
}
