<?php 

namespace App\Http\Controllers;

use App\Models\Performance;
use App\Models\Summary;
use App\Helpers\Input;
use App\Helpers\RunHelper;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SummaryController extends controller {
  public function show($summary_id): View
  {
    $performances = Performance::where('summary_id', $summary_id)->get();
    return view('summary.index', [
      'performances' => $performances,
      'summary_id' => $summary_id,
    ]);
  }
}