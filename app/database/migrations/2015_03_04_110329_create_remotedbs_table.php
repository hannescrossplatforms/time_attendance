<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemotedbsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('remotedbs', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name');
			$table->string('address');
			$table->string('connection');
			$table->string('dbname');

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
		Schema::drop('remotedbs');
	}

}
