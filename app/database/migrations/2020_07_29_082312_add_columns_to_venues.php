<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToVenues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function(Blueprint $table)
		{
			$table->string('sonoff_device_uuid')->nullable()->default(null);
			$table->string('sonoff_device_auth_token')->nullable()->default(null);
			$table->boolean('sonoff_device_on_status')->default(0);
			$table->string('sonoff_device_action_status')->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
