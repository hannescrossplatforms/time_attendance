<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyOpeningHoursToVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function(Blueprint $table)
		{

			$table->string('mon_from', 32);
			$table->string('mon_to', 32);

			$table->string('tue_from', 32);
			$table->string('tue_to', 32);	

			$table->string('wed_from', 32);
			$table->string('wed_to', 32);

			$table->string('thu_from', 32);
			$table->string('thu_to', 32);	

			$table->string('fri_from', 32);
			$table->string('fri_to', 32);	

			$table->string('sat_from', 32);
			$table->string('sat_to', 32);

			$table->string('sun_from', 32);
			$table->string('sun_to', 32);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('venues', function(Blueprint $table)
		{
			//
		});
	}

}
