<?php

namespace App\Http\Controllers\Sekretariat\DataDokumen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipKategori as ArsipKategori;

use Auth;
use DB;
use Session;
use Validator;

class DokumenDibagikanController extends BaseController
{
    public function viewDokumenDibagikan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
       
        return view('sekretariat/data-dokumen/dokumen-dibagikan/view-dokumen-dibagikan',compact('auth_data'));
    }

}