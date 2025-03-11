<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->unsignedBigInteger('eleve_id');
            $table->unsignedBigInteger('classe_id');
            $table->foreign('eleve_id')->references('id')->on('eleves');           
            $table->foreign('classe_id')->references('id')->on('classes');              
            $table->timestamps();                       
            $table->integer('fraisdu');
            $table->integer('fraispayer');
            $table->string('anneescolaire');
        });
    }
    //->onDelete('cascade')->onUpdate('cascade'); ->onDelete('cascade')->onUpdate('cascade')
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscriptions');
    }
}
