<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxThresholdsToBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->integer('max_engaged_length')->after('min_engaged_length');
			$table->integer('max_session_length')->after('min_session_length');
			//
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
