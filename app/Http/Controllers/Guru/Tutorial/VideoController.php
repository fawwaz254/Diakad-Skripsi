<?php

namespace App\Http\Controllers\Guru\Tutorial;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Guru as Guru;
use App\Models\Kota as Kota;
use App\Models\Agama as Agama;
use App\Models\Provinsi as Provinsi;
use App\Models\JenisPekerjaan as JenisPekerjaan;
use App\Models\JenisKepegawaian as JenisKepegawaian;
use App\Models\JenisPtk as JenisPtk;
use App\Models\JenisKeahlianLab as JenisKeahlianLab;
use App\Models\JenisSumberGaji as JenisSumberGaji;
use App\Models\JenisLembagaPengangkat as JenisLembagaPengangkat;
use App\Models\Pengguna;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class VideoController extends BaseController
{
    public function viewVideo(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/tutorial/video/view-video', compact('auth_data'));
    }

}