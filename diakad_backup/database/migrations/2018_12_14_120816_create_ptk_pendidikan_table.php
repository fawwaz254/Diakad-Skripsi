<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePtkPendidikanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ptk_pendidikan', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_ptk_pendidikan', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (diisi salah satu, guru atau staff)');
			$table->string('id_guru', 40)->nullable()->comment('FK: guru.id_guru (diisi salah satu, guru atau staff)');
			$table->string('bidang_studi_pendidikan', 64)->nullable()->comment('Jurusan pada jenjang pendidikan PTK. Untuk pendidikan usia dini, dasar, dan menengah, diisi dengan opsi Umum');
			$table->string('jenjang_pendidikan', 8)->nullable();
			$table->string('gelar_akademik_pendidikan', 16)->nullable();
			$table->string('nm_satuan_pendidikan_formal', 64)->nullable()->comment('Nama satuan pendidikan PTK. Apabila belum lulus, sesuai nomenklatur yang berlaku saat ini. Apabila telah lulus, sesuai nomenklatur yang tercantum pada ijazah. Apabila nama satuan pendidikan terlalu panjang sehingga ada bagian yang tidak dapat termuat atau terpotong, dapat disingkat dengan memperhatikan kesesuaian nama. Contoh: Institut Keguruan dan Ilmu Pendidikan Persatuan Guru Republik Indonesia Pontianak menjadi IKIP-PGRI Pontianak');
			$table->string('fakultas_pendidikan', 64)->nullable()->comment('Nama fakultas pendidikan PTK (khusus jenjang perguruan tinggi). Apabila belum lulus, sesuai nomenklatur yang berlaku saat ini. Apabila telah lulus, sesuai nomenklatur yang tercantum pada ijazah. Apabila nama fakultas terlalu panjang sehingga ada bagian yang tidak termuat atau terpotong, dapat disingkat dengan memperhatikan kesesuaian nama. Contoh: Fakultas Keguruan dan Ilmu Pendidikan menjadi FKIP');
			$table->boolean('status_kependidikan')->nullable()->comment('1 = Ya; 0 = Tidak; (Status pendidikan yang ditempuh, sebagai LPTK (Lembaga Pendidikan Tenaga Keguruan) atau bukan. LPTK adalah lembaga yang khusus mendidik calon-calon guru, seperti STKIP, IKIP, atau FKIP. Pilih Tidak bagi sekolah usia dini, dasar, maupun menengah)');
			$table->integer('tahun_masuk')->nullable();
			$table->integer('tahun_lulus')->nullable();
			$table->string('nomor_induk_pendidikan', 32)->nullable()->comment('Nomor induk PTK saat menempuh pendidikan. NIS atau NISN untuk jenjang pendidikan usia dini, dasar, dan menengah, NIM untuk pendidikan tinggi');
			$table->boolean('is_masih_studi')->nullable()->comment('1 = Ya; 0 = Tidak; (apakah masih studi/kuliah?)');
			$table->boolean('jumlah_semester_pendidikan')->nullable()->comment('Jumlah semester yang berhasil ditempuh pada pendidikan PTK. Contoh jika sekolah selesai ditempuh dalam 3 tahun, maka diisi dengan 9. Jika kuliah selesai ditempuh dalam waktu 4 tahun, maka diisi 8. Jika sekarang masih aktif kuliah di akhir tahun ke-3 maka diisi dengan 6');
			$table->float('rata_rata_nilai', 10, 0)->nullable()->comment('Rata-rata nilai ujian akhir untuk jenjang dasar dan menengah. Nilai IPK (Indeks Prestrasi Akademik) atau GPA (Grade Point Average) bagi pendidikan tinggi. Apabila masih berkuliah, isi dengan nilai IPK/GPA yang paling baru diperoleh');
			$table->timestamps();
			$table->string('created_by', 40)->nullable();
			$table->string('updated_by', 40)->nullable();
			$table->softDeletes();
			$table->string('deleted_by', 40)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ptk_pendidikan');
	}

}
