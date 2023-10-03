<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Yajra\Datatables\Datatables;
use Validator;

class PenggunaTerkunciController extends Controller
{
    public function indexList()
    {
        return view('guru/e-learning-soal/pengguna-terkunci/view-pengguna-terkunci');
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();
        $list_data = Pengguna::with('siswa', 'siswa.kelas')->where('status_join_table', 3)->whereNotNull('terkunci_hingga')->where('terkunci_hingga', '>', now());

        return Datatables::of($list_data)
            ->make(true);
    }

    public function actionUnlock(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'data_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        };

        foreach($input->data_id as $id){
            $pengguna = Pengguna::find($id);
            $pengguna->terkunci_hingga = null;
            $pengguna->save();
        }

        return [
            'status' => 203,
            'message' => 'Berhasil mereset status pengguna'
        ];
    }
}
