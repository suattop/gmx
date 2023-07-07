<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'start_datetime',
        'end_datetime',
        'method',
        'rebalance_hours',
        'glp_weight',
        'short_percentage',
        'min_apr',
        'capital',
        'balance',
        'glp_amount',
        'glp_bal_bf',
        'glp_bal_cf',
        'short_margin_bf',
        'short_margin_cf',
        'short_funding_fees',
        'apr',
        'timestamps'
    ];

    public function performances()
    {
        return $this->hasMany(Performances::class);
    }
}