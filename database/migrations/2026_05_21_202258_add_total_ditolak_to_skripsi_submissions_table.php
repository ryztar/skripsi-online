<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('skripsi_submissions', function (Blueprint $table) {
            $table->integer('total_ditolak')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('skripsi_submissions', function (Blueprint $table) {
            $table->dropColumn('total_ditolak');
        });
    }
};