<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVenueidToSensorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sensors', function(Blueprint $table)
		{
			$table->string('venue_id')->after('ycoord');
			$table->string('location')->after('venue_id');
			$table->string('queue')->after('location');
			$table->string('min_power')->after('queue');
			$table->string('max_power')->after('min_power');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sensors', function(Blueprint $table)
		{
			dropColumn('venue_id');
			dropColumn('location');
			dropColumn('queue');
			dropColumn('min_power');
			dropColumn('max_power');
		});
	}

}
