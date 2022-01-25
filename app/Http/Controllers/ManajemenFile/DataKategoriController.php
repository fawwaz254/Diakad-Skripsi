<?php

namespace App\Http\Controllers\ManajemenFile;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\CategoryFileRole;
use Yajra\Datatables\Datatables;

class DataKategoriController extends BaseController
{
    public function viewDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('manajemen-file/data-kategori/view-data-kategori', compact('auth-data'));
    }

    public function datatablesCategoryfile(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data->pengguna->role_pengguna;
        $curr_id_role = [];
        foreach ($auth_data as $key => $value) {
            $curr_id_role[$key]['id_role'] = $auth_data[$key]['id_role'];
        }
        $list_data = CategoryFileRole::join('category_file', 'category_file.category_file_id', '=', 'category_file_role.category_file_id')
            ->whereIn('category_file_role.id_role', $curr_id_role)
            ->select('category_file.*')->distinct()->get();
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->category_file_id
                );
                return $data;
            })
            ->make(true);
    }
}
