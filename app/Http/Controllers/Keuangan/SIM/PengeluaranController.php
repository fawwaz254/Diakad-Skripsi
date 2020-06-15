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

class PengeluaranController extends BaseController
{
    public function viewMenuPengeluaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        return view('keuangan/sim/pengeluaran/view-menu-pengeluaran', compact('auth_data'));
    }

    public function viewMenuInput(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/sim/pengeluaran/view-menu-input', compact('auth_data'));
    }
}