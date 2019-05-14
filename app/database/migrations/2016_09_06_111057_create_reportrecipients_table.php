<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportrecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reportrecipients', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name');
			$table->string('email');
			$table->integer('absence');
			$table->integer('lateness');
			$table->integer('ws_proximity');
			$table->integer('daily');
			$table->integer('weekly');
			$table->integer('monthly');

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
		Schema::drop('reportrecipients');
	}

}
