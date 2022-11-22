<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->integer('push_ups');
            $table->integer('sit_ups');
            $table->integer('bench_dips');
            $table->integer('squats');
            $table->integer('pull_ups');
            $table->integer('hammer_curl');
            $table->integer('barbel_curl');
            $table->foreignIdFor(\App\Models\User::class,'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
};
