<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminssid1ColumnToVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()

	{
		Schema::table('venues', function($t){

			$t->string("adminssid1")->after("notes")->nullable();
			$t->string("password1", 8)->after("adminssid1")->nullable();
		});

		//
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('venues', function($t) {
		$t->dropColumn("adminssid1");
		$t->dropColumn("password1");
	});
}

}
