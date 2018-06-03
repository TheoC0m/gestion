<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('projects', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 30);
			$table->longText('description');
			$table->date('start');
			$table->date('end');
			$table->enum('status', ['in_progress', 'paused', 'finished', 'stoped']);
			$table->date('real_end');


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
		Schema::dropTable('projects');
    }
}
