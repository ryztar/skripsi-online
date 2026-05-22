<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('skripsi_submissions')) {
            $driver = DB::getDriverName();

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE skripsi_submissions MODIFY status ENUM('draft','submitted','review','revisi','approved','rejected') NOT NULL DEFAULT 'draft'");
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE skripsi_submissions DROP CONSTRAINT IF EXISTS skripsi_submissions_status_check');
                DB::statement("ALTER TABLE skripsi_submissions ADD CONSTRAINT skripsi_submissions_status_check CHECK (status IN ('draft','submitted','review','revisi','approved','rejected'))");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('skripsi_submissions')) {
            $driver = DB::getDriverName();

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE skripsi_submissions MODIFY status ENUM('draft','submitted','review','revisi','approved') NOT NULL DEFAULT 'draft'");
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE skripsi_submissions DROP CONSTRAINT IF EXISTS skripsi_submissions_status_check');
                DB::statement("ALTER TABLE skripsi_submissions ADD CONSTRAINT skripsi_submissions_status_check CHECK (status IN ('draft','submitted','review','revisi','approved'))");
            }
        }
    }
};
