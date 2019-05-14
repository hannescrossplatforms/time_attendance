<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackenabledToVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function(Blueprint $table)
		{
			$table->integer('track_enabled')->after('track_server_location')->default(0);
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
			dropColumn('track_enabled');
		});
	}

}
