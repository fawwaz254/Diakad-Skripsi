<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class SppController extends BaseController
{
    public function viewMenuSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        return view('keuangan/sim/spp/view-menu-spp', compact('auth_data'));
    }
}
