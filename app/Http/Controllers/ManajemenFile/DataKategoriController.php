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
        $auth_data = $input->auth_data->role_aktif->id_role;
        $list_data = CategoryFileRole::join('category_file', 'category_file.category_file_id', '=', 'category_file_role.category_file_id')
            ->where('category_file_role.id_role', $auth_data)
            ->select('category_file.*')->get();
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
