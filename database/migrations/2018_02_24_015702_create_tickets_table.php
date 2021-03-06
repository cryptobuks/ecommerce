<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('password')->nullable();
            $table->string('title');
            $table->text('text');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->bigInteger('category_id');
            //Status: ['close', 'open', 'staff', 'user', 'waiting', 'lock', 'done']
            $table->string('status')->default('open');
            //Priority: ['normal', 'urgent', 'important']
            $table->string('priority')->default('normal');
            $table->ipAddress('ip')->nullable();
            $table->boolean('enabled')->default(true);
            $table->longText('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ticket_replays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('ticket_id');
            $table->text('text');
            //Type: ['normal', 'system', 'forward']
            $table->string('type')->default('normal');
            $table->ipAddress('ip')->nullable();
            $table->boolean('enabled')->default(true);
            $table->longText('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ticket_id');
            $table->bigInteger('replay_id')->nullable();
            $table->string('title')->nullable();
            $table->string('attachment');
            $table->boolean('enabled')->default(true);
            $table->longText('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_replays');
        Schema::dropIfExists('ticket_attachments');
    }
}
