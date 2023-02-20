<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\LibGlobal;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Pengguna as Pengguna;
use App\Models\Siswa as Siswa;
use App\Models\Kelas as Kelas;
use App\Models\Jalur as Jalur;
use App\Models\Semester as Semester;
use App\Models\CalonSiswaBaru as CalonSiswaBaru;
use App\Models\CalonSiswaSekolah as CalonSiswaSekolah;
use App\Models\CalonSiswaOrtu as CalonSiswaOrtu;
use App\Models\CalonSiswaFisik as CalonSiswaFisik;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Penerimaan as Penerimaan;
use App\Models\LogKelasSiswa;
use App\Models\Voucher;
use App\Models\Agama;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\PengajuanWisuda;
use App\Models\KebutuhanKhusus;
use App\Models\JenisTinggal;
use App\Models\JenisTransportasi;
use App\Models\JenisLayakPip;
use App\Models\JenisPendidikan;
use App\Models\JenisPekerjaan;
use App\Models\JenisPenghasilan;
use App\Imports\SiswaImport;
use App\Imports\UploadToInsertUpdateSiswa;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Excel;
use DB;
use Session;
use Validator;
use App\Imports\DataImportExcel;

class UploadDataSiswaController extends BaseController
{
	public function viewUploadDataSiswa(Request $request)
	{
		# code..
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		return view('pendidikan/siswa/upload-data-siswa/view-upload-data-siswa', compact('auth_data'));
	}

	public function downloadFileExcel()
	{
		$file = public_path() . "/excel/ContohFileExcelUploadDataSiswa.xls";
		$headers = [
			'Content-Type' => 'application/xls',
		];

		return response()->download($file, 'ContohFileExcelUploadDataSiswa.xls', $headers);
	}

	public function downloadFileExcelLite()
	{
		$file = public_path() . "/excel/ContohFileExcelUploadDataSiswaLite.xls";
		$headers = [
			'Content-Type' => 'application/xls',
		];

		return response()->download($file, 'ContohFileExcelUploadDataSiswaLite.xls', $headers);
	}

	public function updateDataSiswa(Request $request)
	{

		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now(env('APP_TIMEZONE', ''));

		set_time_limit(0);

		DB::beginTransaction();

		try {

			$path = public_path() . "/excel-ijazah.csv";

			$csv = array();
			$lines = file($path, FILE_IGNORE_NEW_LINES);

			foreach ($lines as $key => $value) {

				$csv[$key] = str_getcsv($value);
			}

			foreach ($csv as $key => $value) {

				$siswa = Siswa::where('nis_siswa', $value[1])->first();

				if ($siswa) {
					$pengjuan_wisuda = PengajuanWisuda::where('id_siswa', $siswa->id_siswa)->first();

					if ($pengjuan_wisuda) {
						$pengjuan_wisuda->nomor_ijasah = $value[4];
						$pengjuan_wisuda->save();
					}
				}
			}

			DB::commit();

			return 'success';
		} catch (\Exception $e) {

			DB::rollback();

			return (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error';
		}
	}



	public function uploadEmailExcel(Request $request)
	{
		// validasi
		// $this->validate($request, [
		// 	'file' => 'required|mimes:csv,xls,xlsx'
		// ]);
		// dd($request);
		// menangkap file excel

		// import data
		Excel::import(new SiswaImport, $request->file('file-excel'));

		// notifikasi dengan session
		return back();
		// // alihkan halaman kembali
		// return redirect('/siswa');
	}


	public function uploadFileExcel(Request $request)
	{
		$input = (object) $request->input();
		$auth_data = $input->auth_data;
		$now = Carbon::now(env('APP_TIMEZONE', ''));
		if ($request->hasFile('file-excel')) {
			// $path = $request->file('file-excel')->getRealPath();
			// $data = Excel::load($path)->get();
			$uploader = new UploadToInsertUpdateSiswa($auth_data, $now);
			$upload = Excel::import($uploader, $request->file('file-excel'));

			$message = $uploader->getMessage();

			return [
				'status'    => $upload ? 200 : 300,
				'message'   => $message[0]
			];
		} else {
			return [
				'status'    => 300, // FAILED
				'message'   => "File Excel Tidak Ditemukan"
			];
		}
	}
}
