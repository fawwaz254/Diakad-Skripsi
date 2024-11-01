<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class RaporController extends BaseController
{
    public function viewSisipan(Request $request)
    {
        return redirect('wali-murid/akademik/rapor-sisipan/cetak-rapor/print/A8bT516992595806548a4bc3075a/A8bT5168964697264b5f77cda6f3');
    }
}
