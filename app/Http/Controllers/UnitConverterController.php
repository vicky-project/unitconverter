<?php

namespace Modules\UnitConverter\Http\Controllers;

use Illuminate\Routing\Controller;
use Nwidart\Modules\Facades\Module;

class UnitConverterController extends Controller
{
  public function index() {
    $notesAvailable = Module::has('Notes') && Module::isEnabled('Notes');
    $notesEndpoint = $notesAvailable ? config('app.url') . '/api/integration/note' : null;

    return view('unitconverter::index', [
      'notesConfig' => [
        'notesAvailable' => $notesAvailable,
        'notesEndpoint' => $notesEndpoint,
      ],
    ]);
  }
}