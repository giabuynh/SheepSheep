<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  use HasFactory;

  protected $primaryKey = 'uname';
  protected $keyType = 'string';
  public $timestamps = false;
}
