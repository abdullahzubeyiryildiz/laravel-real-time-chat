<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->unsignedBigInteger('sent_to_id')->nullable();
            $table->text('message');

            $table->enum('read_at',['0','1'])->default('0');
            $table->enum('status',['0','1'])->default('1');
            $table->timestamps();

            $table->foreign('sender_id')
                  ->references('id')
                  ->on('users');

            $table->foreign('sent_to_id')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
