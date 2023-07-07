<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_id',
        'datetime',
        'capital',
        'balance',
        'glp_amount_bal',
        'glp_value_bal',
        'glp_yield_bal',
        'short_margin_bal',
        'link_short_bal_value',
        'uni_short_bal_value',
        'btc_short_bal_value',
        'eth_short_bal_value',
        'link_short_price',
        'uni_short_price',
        'btc_short_price',
        'eth_short_price',
        'link_short_dex',
        'uni_short_dex',
        'btc_short_dex',
        'eth_short_dex',
        'link_short_amount',
        'uni_short_amount',
        'btc_short_amount',
        'eth_short_amount',
        'link_funding_bal',
        'uni_funding_bal',
        'btc_funding_bal',
        'eth_funding_bal',
        'no_of_rebal',
    ];

    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }
}
