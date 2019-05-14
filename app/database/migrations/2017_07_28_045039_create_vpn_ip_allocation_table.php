<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpnIpAllocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('vpnips', function($t){

			$t->increments('id');
			$t->string('ip_address', 15);
			$t->integer('sensor_id')->unsigned()->nullable();
			$t->boolean('taken')->default(False);
			$t->timestamps();

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
		Schema::drop('vpnips');
	}

}
