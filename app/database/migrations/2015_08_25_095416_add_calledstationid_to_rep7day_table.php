<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalledstationidToRep7dayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rep7day', function(Blueprint $table)
		{
			$table->string('calledstationid')->after("nasid");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rep7day', function(Blueprint $table)
		{
			//
		});
	}

}
