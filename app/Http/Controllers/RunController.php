<?php 

namespace App\Http\Controllers;

use App\Models\Performance;
use App\Models\Summary;
use App\Helpers\Input;
use App\Helpers\RunHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RunController extends controller {
  public function run(Request $request): View
  {
    $parameter = Input::setNew(
      $request->capital,
      $request->start_timestamp,
      $request->period,
      $request->method,
      $request->rebalance_hours,
      $request->glp_weight,
      $request->short_percentage,
      $request->min_apr
    );
    RunHelper::periodic_rebal($parameter);
    $summary = Summary::latest()->first();
    $performances = Performance::where('summary_id', $summary->id)->get();
    return view('run.index', [
      'performances' => $performances,
    ]);
  }
}