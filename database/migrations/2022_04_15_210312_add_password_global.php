<?php

use App\Models\Sekolah;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class AddPasswordGlobal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sekolah', function($table) {
            $table->string('password_global',100)->after('email_sekolah');
        });

        $password_global = Sekolah::where('deleted_at', null)->first();
        $password_global->password_global = Hash::make('passwordglobal');
        $password_global->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
