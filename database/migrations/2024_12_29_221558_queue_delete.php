<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QueueDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('QueueDelete', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('requesparam'); 
            $table->string('folder')->nullable();
            $table->string('uniqkey')->nullable();
            $table->string('type')->nullable();
            $table->integer('iddata')->nullable();
            $table->string('status')->default(0);
            $table->string('is_prosess')->nullable();
            $table->longtext('message')->nullable();
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
        Schema::dropIfExists('QueueDelete');
    }
}
