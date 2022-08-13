<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Media extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service-media', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('images')->unique();
            $table->string('app');
            $table->string('type');
            $table->integer('iddata')->nullable();
            $table->boolean('rowstatus')->default(0);
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
        Schema::dropIfExists('service-media');
    }
}
