<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediaImageDecode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ServiceMediaImageDecode', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('uniqkey'); 
            $table->string('isDecode')->default(0);
            $table->string('status')->nullable();
            $table->string('idmedia')->nullable();
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
        Schema::dropIfExists('ServiceMediaImageDecode');
    }
}
