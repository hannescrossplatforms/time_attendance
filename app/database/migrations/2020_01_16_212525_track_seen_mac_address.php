<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackSeenMacAddress extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('track_seen_mac_address', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('venue_id');
			$table->date('date_seen');
            $table->string('mac_address');
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
		Schema::drop('track_seen_mac_address');
	}

}
