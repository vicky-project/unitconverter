<?php

namespace Modules\UnitConverter\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnitConverterController extends Controller
{
  /**
  * Display a listing of the resource.
  */
  public function index() {
    return view('unitconverter::index');
  }
}