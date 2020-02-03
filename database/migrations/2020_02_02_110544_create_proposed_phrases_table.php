<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposedPhrasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposed_phrases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('phrase_id');
            $table->foreign('phrase_id')->on('phrases')->references('id')->onDelete('cascade');
            $table->string('topic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposed_phrases');
    }
}
