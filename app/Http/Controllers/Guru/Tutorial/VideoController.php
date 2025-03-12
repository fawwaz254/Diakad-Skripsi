<?php

namespace App\Http\Controllers\Guru\Tutorial;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Modul;
use App\Models\Menu;

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
        $auth_data = auth_data();

        $role_id = $auth_data->role_aktif->id_role;
        $role_name = $auth_data->role_aktif->nm_role;

        $modul = Modul::where('id_role',$role_id)->where('akses',1)->get();

        return view('guru/tutorial/video/view-video', compact('auth_data','modul','role_name'));
    }

    public function viewVideoModul(Request $request,$id_modul)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $modul = Modul::find($id_modul);
        $menu = Menu::where('id_modul',$id_modul)->where('akses',1)->get();

        return view('guru/tutorial/video/view-video-modul', compact('auth_data','modul','menu'));

    }

    public function viewVideoMenu(Request $request,$id_menu)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $menu = Menu::find($id_menu);

        return view('guru/tutorial/video/view-video-menu', compact('auth_data','menu'));

    }

}