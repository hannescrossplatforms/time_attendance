<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FlagsForProductActivationInVenues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function(Blueprint $table)
		{
			$table->integer('wifi_activated')->after('location')->default(0);
			$table->integer('rm_activated')->after('wifi_activated')->default(0);
			$table->integer('jam_activated')->after('rm_activated')->default(0);
			$table->integer('engage_activated')->after('jam_activated')->default(0);
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
