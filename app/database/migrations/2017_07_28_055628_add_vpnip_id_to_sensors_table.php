<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVpnipIdToSensorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('sensors', function($t){

			$t->integer('vpnip_id')->after('mac')->unsigned()->default(1);
			//$t->foreign('vpnip_id')->references('id')->on('vpnips');
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
		dropForeign('vpnip_id');
		dropColumn('vpnip_id');
	}

}
