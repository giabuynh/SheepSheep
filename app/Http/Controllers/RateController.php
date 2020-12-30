<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rate as RateModel;


class RateController extends Controller
{
  function rate(Request $request) {
    // return $request->uname;
    $rate = RateModel::where('blog', '=', $request->blog)->where('uname', '=', $request->uname);
    $saved = false;
    if ($rate != null)
      $rate->delete();

    $new_rate = new RateModel;

    $new_rate->blog = $request->blog;
    $new_rate->uname = $request->uname;
    $new_rate->stars = $request->stars;

    $saved = $new_rate->save();

    // return "yep";
    // return $request->input('blog');
    if ($saved) return true;
    return false;
  }
}
