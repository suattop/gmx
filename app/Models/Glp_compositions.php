<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Glp_compositions extends Model 
{
  use HasFactory;

  protected $fillable = [
    'datetime',
    'token',
    'amount',
    'cumulative_amount',
    'value',
    'cumulative_value',
    'price',
  ];
  
}