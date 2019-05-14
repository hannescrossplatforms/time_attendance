<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FlagsForProductActivationInBrands extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->integer('wifi_activated')->after('hipwifi')->default(0);
			$table->integer('rm_activated')->after('hiprm')->default(0);
			$table->integer('jam_activated')->after('hipjam')->default(0);
			$table->integer('engage_activated')->after('hipengage')->default(0);
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			//
		});
	}

}
