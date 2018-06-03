<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectUserMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('project_user', function(Blueprint $table) {


			$table->unsignedInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users');

			$table->unsignedInteger('project_id');
			$table->foreign('project_id')->references('id')->on('projects');

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
		Schema::dropTable('project_user');
    }
}
