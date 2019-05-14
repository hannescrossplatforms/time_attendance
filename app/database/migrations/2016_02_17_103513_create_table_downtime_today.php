<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDowntimeToday extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('downtime_today', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nasid');
			$table->date('date');
			$table->time('time');
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
		Schema::drop('downtime_today');
	}

}
