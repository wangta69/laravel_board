<?php
namespace Pondol\Bbs\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Validator;
use Response;
use Pondol\Bbs\Models\BbsTables;

use App\Http\Controllers\Controller;

class WelcomeController  extends Controller {

  public function __construct() {

  }

  public function welcome() {

    $items = BbsTables::orderBy('created_at', 'desc')->get();
    return view('bbs::welcome', compact('items'));
  }
 
}