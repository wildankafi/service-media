<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MediaImg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ServiceMediaImage', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('uniqkey')->unique(); 
            $table->longtext('images'); 
            $table->longtext('mimetype'); 
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
        Schema::dropIfExists('ServiceMediaImage');
    }
}
