<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaskUserMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('task_user', function(Blueprint $table) {


			$table->unsignedInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');

			$table->unsignedInteger('task_id');
			$table->foreign('task_id')->references('id')->on('tasks');


			$table->integer('estimation');

			$table->timestamps();
			$table->softDeletes();

			$table->engine = 'InnoDB';
			$table->charset = 'utf8';
			$table->collation = 'utf8_unicode_ci';
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropTable('task_user');
    }
}
