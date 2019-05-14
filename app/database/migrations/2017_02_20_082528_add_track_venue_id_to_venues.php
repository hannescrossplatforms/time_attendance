<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackVenueIdToVenues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function(Blueprint $table)
		{
			// $table->string('track_venue_id');
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
			// $table->dropColumn('track_venue_id');
		});
	}

}
