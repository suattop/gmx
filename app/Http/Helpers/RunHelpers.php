<?php

namespace App\Helpers;

use App\Models\Performance;
use App\Models\Summary;
use App\Models\Price;
use App\Models\Glp_compositions;
use PhpParser\Node\Stmt\Else_;

class Input
{
  public string $capital = '10000';
  public string $start_timestamp = '2023-01-01 00:00:00';
  public string $period = '30';
  public string $method = 'periodic_rebal';
  public string $rebalance_hours = '24';
  public string $glp_weight = '0.5';
  public string $short_percentage = '0.9';
  public string $min_apr = '0.01';

  public function __construct($new)
  {
    $this->capital = $new['capital'];
    $this->start_timestamp = $new['start_timestamp'];
    $this->period = $new['period'];
    $this->method = $new['method'];
    $this->rebalance_hours = $new['rebalance_hours'];
    $this->glp_weight = $new['glp_weight'];
    $this->short_percentage = $new['short_percentage'];
    $this->min_apr = $new['min_apr'];
  }

  public static function setNew($capital, $start_timestamp, $period, $method, $rebalance_hours, $glp_weight, $short_percentage, $min_apr)
  {
    $parameter = new Input([
      'capital' => $capital,
      'start_timestamp' => $start_timestamp,
      'period' => $period,
      'method' => $method,
      'rebalance_hours' => $rebalance_hours,
      'glp_weight' => $glp_weight,
      'short_percentage' => $short_percentage,
      'min_apr' => $min_apr
    ]);
    return $parameter;
  }
}
class RunHelper
{

  public static function periodic_rebal(Input $parameter)
  {
    /** convert start_timestamp to datetime format */
    $start_datetime = date('Y-m-d H:i:s', $parameter->start_timestamp);
    $end_datetime = date('Y-m-d H:i:s', $parameter->start_timestamp + $parameter->period * 60 * 60 * 24);
    $prices = Price::where('datetime', '>=', $start_datetime)
      ->where('datetime', '<=', $end_datetime)
      ->orderBy('datetime', 'asc')->get();
    $compositions = Glp_compositions::where('datetime', '>=', $start_datetime)
      ->where('datetime', '<=', $end_datetime)
      ->orderBy('datetime', 'asc')->get();
    Summary::create(
      [
        'start_datetime' => $start_datetime,
        'end_datetime' => $end_datetime,
        'method' => $parameter->method,
        'rebalance_hours' => $parameter->rebalance_hours,
        'glp_weight' => $parameter->glp_weight,
        'short_percentage' => $parameter->short_percentage,
        'min_apr' => $parameter->min_apr,
        'capital' => $parameter->capital,
        'balance' => 0,
        'glp_amount' => 0,
        'glp_bal_bf' => 0,
        'glp_bal_cf' => 0,
        'short_margin_bf' => 0,
        'short_margin_cf' => 0,
        'short_funding_fees' => 0,
        'apr' => 0
      ]
    );
    $summary_id = Summary::all()->last()->id;;
    $datetimes = $prices->where('token', 'GLP')->pluck('datetime');
    $glp_start_price = $prices->where('datetime', $start_datetime)->where('token', 'GLP')->first()->close;
    $glp_start_composition = $compositions->where('datetime', $start_datetime);
    $sum = $glp_start_composition->sum('cumulative_value');
    $glp_start_amount_bal = $parameter->capital*$parameter->glp_weight / $glp_start_price;
    $glp_start_value_bal = $glp_start_amount_bal * $glp_start_price;
    $leverage = round($parameter->glp_weight / (1 - $parameter->glp_weight), 1, PHP_ROUND_HALF_DOWN);
    $link_start_short_amount = ($glp_start_composition->where('token', 'LINK')->first()->cumulative_value / $sum) * $parameter->capital * $parameter->glp_weight / $glp_start_composition->where('token', 'LINK')->first()->price;
    $uni_start_short_amount = $glp_start_composition->where('token', 'UNI')->first()->cumulative_value / $sum * $parameter->capital * $parameter->glp_weight / $glp_start_composition->where('token', 'UNI')->first()->price;
    $eth_start_short_amount = $glp_start_composition->where('token', 'ETH')->first()->cumulative_value / $sum * $parameter->capital * $parameter->glp_weight / $glp_start_composition->where('token', 'ETH')->first()->price;
    $btc_start_short_amount = $glp_start_composition->where('token', 'BTC')->first()->cumulative_value / $sum * $parameter->capital * $parameter->glp_weight / $glp_start_composition->where('token', 'BTC')->first()->price;
    $link_start_short_price = $prices->where('token', 'LINK')->first()->close;
    $uni_start_short_price = $prices->where('token', 'UNI')->first()->close;
    $eth_start_short_price = $prices->where('token', 'ETH')->first()->close;
    $btc_start_short_price = $prices->where('token', 'BTC')->first()->close;
    $start_short_margin_bal = $parameter->capital * (1 - $parameter->glp_weight);
    for ($i = 0; $i < $datetimes->count(); $i++) {
      Performance::create([
        /** create according to Performance model fillable */
        'summary_id' => $summary_id,
        'datetime' => $datetimes[$i],
        'capital' => $parameter->capital,
        'balance' => 0,
        'glp_amount_bal' => $glp_start_amount_bal,
        'glp_value_bal' => $glp_start_value_bal,
        'glp_yield_bal' => 0,
        'short_margin_bal' => $start_short_margin_bal,
        'link_short_bal_value' => 0,
        'uni_short_bal_value' => 0,
        'btc_short_bal_value' => 0,
        'eth_short_bal_value' => 0,
        'link_short_price' => $link_start_short_price,
        'uni_short_price' => $uni_start_short_price,
        'btc_short_price' => $btc_start_short_price,
        'eth_short_price' => $eth_start_short_price,
        'link_short_amount' => $link_start_short_amount,
        'uni_short_amount' => $uni_start_short_amount,
        'btc_short_amount' => $btc_start_short_amount,
        'eth_short_amount' => $eth_start_short_amount,
        'link_funding_bal' => 0,
        'uni_funding_bal' => 0,
        'btc_funding_bal' => 0,
        'eth_funding_bal' => 0,
        'no_of_rebal' => 0,
      ]);
    }
    $performances = Performance::where('summary_id', $summary_id)->get();

    for ($i = 0; $i < $performances->count(); $i++) {
      if ($i > 0) {
        $currentPrice = $prices->where('datetime', $performances[$i]->datetime);
        $performances[$i]->glp_amount_bal =  $performances[$i - 1]->glp_amount_bal;
        $performances[$i]->glp_value_bal = $performances[$i]->glp_amount_bal * $currentPrice->where('token', 'GLP')->first()->close;
        if (fmod($i,($parameter->rebalance_hours / 24)) == 0) {
          $glp_composition = $compositions->where('datetime', $performances[$i]->datetime);
          $performances[$i]->link_short_amount = $glp_composition->where('token', 'LINK')->first()->cumulative_value / $sum * $performances[$i]->glp_value_bal  / $glp_composition->where('token', 'LINK')->first()->price;
          $performances[$i]->uni_short_amount = $glp_composition->where('token', 'UNI')->first()->cumulative_value / $sum * $performances[$i]->glp_value_bal  / $glp_composition->where('token', 'UNI')->first()->price;
          $performances[$i]->btc_short_amount = $glp_composition->where('token', 'BTC')->first()->cumulative_value / $sum * $performances[$i]->glp_value_bal  / $glp_composition->where('token', 'BTC')->first()->price;
          $performances[$i]->eth_short_amount =$glp_composition->where('token', 'ETH')->first()->cumulative_value / $sum * $performances[$i]->glp_value_bal  / $glp_composition->where('token', 'ETH')->first()->price;
          $performances[$i]->link_short_price = ($performances[$i - 1]->link_short_price * $performances[$i - 1]->link_short_amount +  $currentPrice->where('token', 'LINK')->first()->close * ($performances[$i]->link_short_amount - $performances[$i - 1]->link_short_amount)) / $performances[$i]->link_short_amount;
          $performances[$i]->uni_short_price = ($performances[$i - 1]->uni_short_price * $performances[$i - 1]->uni_short_amount +  $currentPrice->where('token', 'UNI')->first()->close * ($performances[$i]->uni_short_amount - $performances[$i - 1]->uni_short_amount)) / $performances[$i]->uni_short_amount;
          $performances[$i]->btc_short_price = ($performances[$i - 1]->btc_short_price * $performances[$i - 1]->btc_short_amount +  $currentPrice->where('token', 'BTC')->first()->close * ($performances[$i]->btc_short_amount - $performances[$i - 1]->btc_short_amount)) / $performances[$i]->btc_short_amount;
          $performances[$i]->eth_short_price = ($performances[$i - 1]->eth_short_price * $performances[$i - 1]->eth_short_amount +  $currentPrice->where('token', 'ETH')->first()->close* ($performances[$i]->eth_short_amount - $performances[$i - 1]->eth_short_amount)) / $performances[$i]->eth_short_amount;
          $performances[$i]->no_of_rebal = $performances->max('no_of_rebal') + 1;
        } else {
          $performances[$i]->link_short_amount = $performances[$i-1]->link_short_amount;
          $performances[$i]->uni_short_amount = $performances[$i-1]->uni_short_amount;
          $performances[$i]->btc_short_amount = $performances[$i-1]->btc_short_amount;
          $performances[$i]->eth_short_amount = $performances[$i-1]->eth_short_amount;
          $performances[$i]->link_short_price = $performances[$i-1]->link_short_price;
          $performances[$i]->uni_short_price = $performances[$i-1]->uni_short_price;
          $performances[$i]->btc_short_price = $performances[$i-1]->btc_short_price;
          $performances[$i]->eth_short_price = $performances[$i-1]->eth_short_price;
          $performances[$i]->no_of_rebal = 0;
        }
        $performances[$i]->link_short_bal_value = ($performances[$i]->link_short_price - $currentPrice->where('token', 'LINK')->first()->close) * $performances[$i]->link_short_amount;
        $performances[$i]->uni_short_bal_value = ($performances[$i]->uni_short_price - $currentPrice->where('token', 'UNI')->first()->close) * $performances[$i]->uni_short_amount;
        $performances[$i]->btc_short_bal_value = ($performances[$i]->btc_short_price - $currentPrice->where('token', 'BTC')->first()->close) * $performances[$i]->btc_short_amount;
        $performances[$i]->eth_short_bal_value = ($performances[$i]->eth_short_price - $currentPrice->where('token', 'ETH')->first()->close) * $performances[$i]->eth_short_amount;
        $performances[$i]->short_margin_bal = $performances[$i - 1]->short_margin_bal + $performances[$i]->link_short_bal_value + $performances[$i]->uni_short_bal_value + $performances[$i]->btc_short_bal_value + $performances[$i]->eth_short_bal_value;
        $performances[$i]->balance = $performances[$i]->glp_value_bal + $performances[$i]->glp_yield_bal + $performances[$i]->short_margin_bal + $performances[$i]->link_funding_bal + $performances[$i]->uni_funding_bal + $performances[$i]->btc_funding_bal + $performances[$i]->eth_funding_bal;
      }
      $performances[$i]->save();
    }
  }
}
