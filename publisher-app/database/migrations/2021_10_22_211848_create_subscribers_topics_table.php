<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained('subscribers')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('subscribers_topics');
    }
}
