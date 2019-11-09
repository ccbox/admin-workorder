<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminWorkorderTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_workorder_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->unsignedInteger('user_id');
            $table->string('username', 128);
            $table->string('nickname', 128);
            $table->string('rolename', 128)->nullable();

            $table->string('type',32);
            $table->string('title',128);
            $table->text('content');
            $table->text('images')->nullable();
            
            $table->tinyInteger('level');
            
            $table->boolean('closed')->default(false);
            $table->dateTime('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('admin_workorder_tickets');
    }
}
