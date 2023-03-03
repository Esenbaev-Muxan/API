<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('task_user', function (Blueprint $table) {
            $table->unsignedBigInteger('suggested_by')->nullable();
            $table->foreign('suggested_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('task_user', function (Blueprint $table) {
            $table->dropForeign(['suggested_by']);
            $table->dropColumn('suggested_by');
        });
    }
    
};
