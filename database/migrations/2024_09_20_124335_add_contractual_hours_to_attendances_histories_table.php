<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractualHoursToAttendancesHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('attendance_histories', function (Blueprint $table) {
            $table->double('contractual_hours')->nullable(); // Add nullable if you want it to be optional
        });
    }

    public function down()
    {
        Schema::table('attendances_histories', function (Blueprint $table) {
            $table->dropColumn('contractual_hours');
        });
    }
}