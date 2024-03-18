<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function index()
    {
        $check_sekolah = Sekolah::first();
        if ($check_sekolah->nm_singkat_sekolah == 'manu') {
            return view('public.pimpinan.ytpnu');
        } else {
            return 'PAGE NOT FOUND';
        }
    }
}
